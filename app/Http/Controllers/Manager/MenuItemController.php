<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\MenuItem;
use App\Models\Restaurant;
use App\Services\UploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MenuItemController extends Controller
{
    public function __construct(private UploadService $uploads) {}

    public function index(Request $request)
    {
        $restaurantId = (int) $request->attributes->get('restaurant_id');

        $items = MenuItem::query()
            ->where('restaurant_id', $restaurantId)
            ->with('category')
            ->orderBy('display_order')
            ->paginate(30);

        return view('manager.menu-items.index', [
            'items' => $items,
            'restaurant' => Restaurant::findOrFail($restaurantId),
        ]);
    }

    public function create(Request $request)
    {
        $restaurantId = (int) $request->attributes->get('restaurant_id');

        return view('manager.menu-items.form', [
            'item' => new MenuItem(['is_available' => true, 'display_order' => 0, 'price' => 0]),
            'categories' => Category::where('restaurant_id', $restaurantId)->orderBy('name')->get(),
            'restaurant' => Restaurant::findOrFail($restaurantId),
        ]);
    }

    public function store(Request $request)
    {
        $restaurantId = (int) $request->attributes->get('restaurant_id');
        $data = $this->validated($request, $restaurantId);

        if ($request->hasFile('image')) {
            $upload = $this->uploads->storeImage($request->file('image'), 'menu-items');
            if (! $upload['success']) {
                return back()->withErrors(['image' => $upload['message']])->withInput();
            }
            $data['image'] = $upload['filename'];
        }

        MenuItem::create($data);

        return redirect()->route('manager.menu-items.index')->with('success', 'Menu item created.');
    }

    public function edit(Request $request, MenuItem $menuItem)
    {
        $this->authorizeRestaurant($request, $menuItem);
        $restaurantId = (int) $request->attributes->get('restaurant_id');

        return view('manager.menu-items.form', [
            'item' => $menuItem,
            'categories' => Category::where('restaurant_id', $restaurantId)->orderBy('name')->get(),
            'restaurant' => Restaurant::findOrFail($restaurantId),
        ]);
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

        return redirect()->route('manager.menu-items.index')->with('success', 'Menu item updated.');
    }

    public function destroy(Request $request, MenuItem $menuItem)
    {
        $this->authorizeRestaurant($request, $menuItem);
        $this->uploads->delete('menu-items', $menuItem->image);
        $menuItem->delete();

        return redirect()->route('manager.menu-items.index')->with('success', 'Menu item deleted.');
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
