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

        return view('manager.categories.index', [
            'categories' => $categories,
            'restaurant' => Restaurant::findOrFail($restaurantId),
        ]);
    }

    public function create(Request $request)
    {
        $restaurantId = (int) $request->attributes->get('restaurant_id');

        return view('manager.categories.form', [
            'category' => new Category(['is_active' => true, 'display_order' => 0]),
            'sections' => Section::where('restaurant_id', $restaurantId)->orderBy('display_order')->get(),
            'restaurant' => Restaurant::findOrFail($restaurantId),
            'secondarySectionIds' => [],
        ]);
    }

    public function store(Request $request)
    {
        $restaurantId = (int) $request->attributes->get('restaurant_id');
        if (! $this->subscriptions->canAddCategory($restaurantId)) {
            return back()->withErrors(['limit' => 'Category limit reached for your plan. Please upgrade.'])->withInput();
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
        $this->secondarySections->sync(
            $category->id,
            (int) $data['section_id'],
            array_map('intval', $request->input('secondary_section_ids', [])),
        );

        return redirect()->route('manager.categories.index')->with('success', 'Category created.');
    }

    public function edit(Request $request, Category $category)
    {
        $this->authorizeRestaurant($request, $category);
        $restaurantId = (int) $request->attributes->get('restaurant_id');

        return view('manager.categories.form', [
            'category' => $category,
            'sections' => Section::where('restaurant_id', $restaurantId)->orderBy('display_order')->get(),
            'restaurant' => Restaurant::findOrFail($restaurantId),
            'secondarySectionIds' => $this->secondarySections->sectionIdsForCategory($category->id),
        ]);
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
        $this->secondarySections->sync(
            $category->id,
            (int) $data['section_id'],
            array_map('intval', $request->input('secondary_section_ids', [])),
        );

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
