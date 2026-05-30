<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Restaurant;
use App\Models\Section;
use App\Services\CategorySecondarySectionService;
use App\Services\SubscriptionService;
use App\Services\UploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function __construct(
        private UploadService $uploads,
        private SubscriptionService $subscriptions,
        private CategorySecondarySectionService $secondarySections,
    ) {}

    public function index(Request $request)
    {
        $restaurantId = (int) $request->attributes->get('restaurant_id');

        $categories = Category::query()
            ->where('restaurant_id', $restaurantId)
            ->with('section')
            ->withCount('menuItems')
            ->orderBy('display_order')
            ->get();

        $editCategory = null;
        $secondarySectionIds = [];
        if ($request->filled('edit')) {
            $editCategory = Category::query()
                ->where('restaurant_id', $restaurantId)
                ->where('id', $request->integer('edit'))
                ->first();
            if ($editCategory) {
                $secondarySectionIds = $this->secondarySections->sectionIdsForCategory($editCategory->id);
            }
        }

        $sections = Section::query()
            ->where('restaurant_id', $restaurantId)
            ->where('is_active', 1)
            ->orderBy('display_order')
            ->orderBy('name')
            ->get();

        return view('manager.categories.index', [
            'categories' => $categories,
            'sections' => $sections,
            'restaurant' => Restaurant::findOrFail($restaurantId),
            'uploadUrl' => rtrim(config('resmenu.canonical_upload_url') ?: config('resmenu.upload_url'), '/'),
            'editCategory' => $editCategory,
            'secondarySectionIds' => $secondarySectionIds,
            'openCreateModal' => $request->query('open') === 'create',
        ]);
    }

    public function create(Request $request)
    {
        return redirect()->route('manager.categories.index', ['open' => 'create']);
    }

    public function store(Request $request)
    {
        $restaurantId = (int) $request->attributes->get('restaurant_id');

        if (! $this->subscriptions->canAddCategory($restaurantId)) {
            return back()
                ->withErrors(['limit' => 'Category limit reached for your plan. Please upgrade.'])
                ->withInput();
        }

        $data = $this->validated($request, $restaurantId);

        if ($request->hasFile('image')) {
            $upload = $this->uploads->storeImage($request->file('image'), 'categories');
            if (! $upload['success']) {
                return back()->withErrors(['image' => $upload['message']])->withInput();
            }
            $data['image'] = $upload['filename'];
        }

        $category = Category::create($data);
        $this->syncSecondarySections($request, $category->id, (int) $data['section_id']);

        return redirect()->route('manager.categories.index')->with('success', 'Category created.');
    }

    public function edit(Request $request, Category $category)
    {
        $this->authorizeRestaurant($request, $category);

        return redirect()->route('manager.categories.index', ['edit' => $category->id]);
    }

    public function update(Request $request, Category $category)
    {
        $this->authorizeRestaurant($request, $category);
        $restaurantId = (int) $request->attributes->get('restaurant_id');
        $data = $this->validated($request, $restaurantId, $category->id);

        if ($request->hasFile('image')) {
            $upload = $this->uploads->storeImage($request->file('image'), 'categories');
            if (! $upload['success']) {
                return back()->withErrors(['image' => $upload['message']])->withInput();
            }
            $this->uploads->delete('categories', $category->image);
            $data['image'] = $upload['filename'];
        }

        $category->update($data);
        $this->syncSecondarySections($request, $category->id, (int) $data['section_id']);

        return redirect()->route('manager.categories.index')->with('success', 'Category updated.');
    }

    public function destroy(Request $request, Category $category)
    {
        $this->authorizeRestaurant($request, $category);
        $this->uploads->delete('categories', $category->image);
        $category->menuItems()->delete();
        $category->delete();

        return redirect()->route('manager.categories.index')->with('success', 'Category deleted.');
    }

    private function syncSecondarySections(Request $request, int $categoryId, int $primarySectionId): void
    {
        $this->secondarySections->sync(
            $categoryId,
            $primarySectionId,
            array_map('intval', $request->input('secondary_section_ids', [])),
        );
    }

    private function validated(Request $request, int $restaurantId, ?int $ignoreId = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'section_id' => ['required', 'integer'],
            'description' => ['nullable', 'string'],
            'display_order' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
            'image' => ['nullable', 'image', 'max:5120'],
        ]);

        $slug = Str::slug($data['name']);
        $exists = Category::query()
            ->where('restaurant_id', $restaurantId)
            ->where('slug', $slug)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists();
        if ($exists) {
            $slug .= '-'.Str::random(4);
        }

        return [
            'restaurant_id' => $restaurantId,
            'section_id' => (int) $data['section_id'],
            'name' => $data['name'],
            'slug' => $slug,
            'description' => $data['description'] ?? null,
            'display_order' => (int) ($data['display_order'] ?? 0),
            'is_active' => $request->boolean('is_active', true),
        ];
    }

    private function authorizeRestaurant(Request $request, Category $category): void
    {
        if ((int) $category->restaurant_id !== (int) $request->attributes->get('restaurant_id')) {
            abort(403);
        }
    }
}
