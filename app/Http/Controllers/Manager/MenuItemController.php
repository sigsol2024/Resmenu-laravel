<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\MenuItem;
use App\Models\Restaurant;
use App\Services\SubscriptionService;
use App\Services\UploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MenuItemController extends Controller
{
    public function __construct(
        private UploadService $uploads,
        private SubscriptionService $subscriptions,
    ) {}

    public function index(Request $request)
    {
        $restaurantId = (int) $request->attributes->get('restaurant_id');
        $categoryId = $request->integer('category_id') ?: null;

        $query = MenuItem::query()
            ->where('restaurant_id', $restaurantId)
            ->with('category')
            ->orderBy('display_order');

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        $items = $query->paginate(30)->withQueryString();

        $editItem = null;
        if ($request->filled('edit')) {
            $editItem = MenuItem::query()
                ->where('restaurant_id', $restaurantId)
                ->where('id', $request->integer('edit'))
                ->first();
        }

        $categories = Category::query()
            ->where('restaurant_id', $restaurantId)
            ->where('is_active', 1)
            ->orderBy('display_order')
            ->orderBy('name')
            ->get();

        return view('manager.menu-items.index', [
            'items' => $items,
            'categories' => $categories,
            'selectedCategoryId' => $categoryId,
            'restaurant' => Restaurant::findOrFail($restaurantId),
            'uploadUrl' => rtrim(config('resmenu.canonical_upload_url') ?: config('resmenu.upload_url'), '/'),
            'editItem' => $editItem,
            'openCreateModal' => $request->query('open') === 'create',
        ]);
    }

    public function create(Request $request)
    {
        return redirect()->route('manager.menu-items.index', $this->indexQueryParams($request, ['open' => 'create']));
    }

    public function store(Request $request)
    {
        $restaurantId = (int) $request->attributes->get('restaurant_id');

        if (! $this->subscriptions->canAddMenuItem($restaurantId)) {
            return back()
                ->withErrors(['limit' => 'Menu item limit reached for your plan. Please upgrade.'])
                ->withInput();
        }

        $data = $this->validated($request, $restaurantId);

        if ($request->hasFile('image')) {
            $upload = $this->uploads->storeImage($request->file('image'), 'menu-items');
            if (! $upload['success']) {
                return back()->withErrors(['image' => $upload['message']])->withInput();
            }
            $data['image'] = $upload['filename'];
        }

        MenuItem::create($data);

        return redirect()->route('manager.menu-items.index', $this->indexQueryParams($request))
            ->with('success', 'Menu item created.');
    }

    public function edit(Request $request, MenuItem $menuItem)
    {
        $this->authorizeRestaurant($request, $menuItem);

        return redirect()->route('manager.menu-items.index', $this->indexQueryParams($request, ['edit' => $menuItem->id]));
    }

    public function update(Request $request, MenuItem $menuItem)
    {
        $this->authorizeRestaurant($request, $menuItem);
        $restaurantId = (int) $request->attributes->get('restaurant_id');
        $data = $this->validated($request, $restaurantId, $menuItem->id);

        if ($request->hasFile('image')) {
            $upload = $this->uploads->storeImage($request->file('image'), 'menu-items');
            if (! $upload['success']) {
                return back()->withErrors(['image' => $upload['message']])->withInput();
            }
            $this->uploads->delete('menu-items', $menuItem->image);
            $data['image'] = $upload['filename'];
        }

        $menuItem->update($data);

        return redirect()->route('manager.menu-items.index', $this->indexQueryParams($request))
            ->with('success', 'Menu item updated.');
    }

    public function destroy(Request $request, MenuItem $menuItem)
    {
        $this->authorizeRestaurant($request, $menuItem);
        $this->uploads->delete('menu-items', $menuItem->image);
        $menuItem->delete();

        return redirect()->route('manager.menu-items.index', $this->indexQueryParams($request))
            ->with('success', 'Menu item deleted.');
    }

    /** @param  array<string, mixed>  $extra */
    private function indexQueryParams(Request $request, array $extra = []): array
    {
        $categoryId = $request->input('_return_category_id', $request->query('category_id'));

        return array_filter(array_merge(
            ['category_id' => $categoryId ?: null],
            $extra,
        ), fn ($value) => $value !== null && $value !== '');
    }

    private function validated(Request $request, int $restaurantId, ?int $ignoreId = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'integer'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'display_order' => ['nullable', 'integer'],
            'is_available' => ['nullable', 'boolean'],
            'image' => ['nullable', 'image', 'max:5120'],
        ]);

        $slug = Str::slug($data['name']);
        $exists = MenuItem::query()
            ->where('restaurant_id', $restaurantId)
            ->where('slug', $slug)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists();
        if ($exists) {
            $slug .= '-'.Str::random(4);
        }

        return [
            'restaurant_id' => $restaurantId,
            'category_id' => (int) $data['category_id'],
            'name' => $data['name'],
            'slug' => $slug,
            'description' => $data['description'] ?? null,
            'price' => $data['price'],
            'display_order' => (int) ($data['display_order'] ?? 0),
            'is_available' => $request->boolean('is_available', true),
        ];
    }

    private function authorizeRestaurant(Request $request, MenuItem $item): void
    {
        if ((int) $item->restaurant_id !== (int) $request->attributes->get('restaurant_id')) {
            abort(403);
        }
    }
}
