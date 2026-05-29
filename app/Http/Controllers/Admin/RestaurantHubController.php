<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\MenuItem;
use App\Models\Restaurant;
use App\Models\Section;
use App\Models\Subscription;
use App\Services\CategorySecondarySectionService;
use App\Services\CustomizationService;
use App\Services\UploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class RestaurantHubController extends Controller
{
    public function __construct(
        private UploadService $uploads,
        private CustomizationService $customization,
        private CategorySecondarySectionService $secondarySections,
    ) {}

    public function show(Request $request, Restaurant $restaurant)
    {
        if ($request->isMethod('post')) {
            return $this->handlePost($request, $restaurant);
        }

        $restaurant->load(['sections', 'categories.section']);
        $tab = $request->query('tab', 'menu');
        $editAction = $request->query('action');
        $editId = (int) $request->query('id');

        $editMenuItem = ($editAction === 'edit_menu_item' && $editId > 0)
            ? MenuItem::where('restaurant_id', $restaurant->id)->with('category')->find($editId)
            : null;

        $menuItems = MenuItem::where('restaurant_id', $restaurant->id)
            ->with('category')
            ->orderBy('display_order')
            ->orderByDesc('id')
            ->paginate(50)
            ->withQueryString();

        $menuTemplates = Schema::hasTable('templates')
            ? DB::table('templates')->where('is_active', 1)->orderBy('id')->get()
            : collect(range(1, 18))->map(fn ($id) => (object) ['id' => $id, 'name' => 'Template '.$id]);

        return view('admin.restaurants.hub', [
            'restaurant' => $restaurant,
            'subscription' => Subscription::where('restaurant_id', $restaurant->id)->orderByDesc('id')->with('plan')->first(),
            'sections' => $restaurant->sections,
            'categories' => Category::where('restaurant_id', $restaurant->id)->with('section')->withCount('menuItems')->orderBy('display_order')->get(),
            'menuItems' => $menuItems,
            'editMenuItem' => $editMenuItem,
            'customization' => $this->customization->forRestaurant($restaurant),
            'activeTab' => $tab,
            'headerMenuItems' => json_decode($restaurant->header_menu_items ?? '[]', true) ?: [],
            'menuTemplates' => $menuTemplates,
            'showBackToDashboard' => true,
        ]);
    }

    private function handlePost(Request $request, Restaurant $restaurant)
    {
        $action = $request->input('action', '');

        return match ($action) {
            'save_template' => $this->saveTemplate($request, $restaurant),
            'save_customization' => $this->saveCustomization($request, $restaurant),
            'save_header_footer' => $this->saveHeaderFooter($request, $restaurant),
            'create_category', 'update_category' => $this->saveCategory($request, $restaurant, $action),
            'delete_category' => $this->deleteCategory($request, $restaurant),
            'create_menu_item', 'update_menu_item' => $this->saveMenuItem($request, $restaurant, $action),
            'delete_menu_item' => $this->deleteMenuItem($request, $restaurant),
            default => back()->withErrors(['action' => 'Unknown action']),
        };
    }

    private function saveTemplate(Request $request, Restaurant $restaurant)
    {
        $restaurant->update(['template_id' => (int) $request->input('template_id', 1)]);

        return redirect()->route('admin.restaurants.hub', [$restaurant, 'tab' => 'customization'])->with('success', 'Template updated.');
    }

    private function saveCustomization(Request $request, Restaurant $restaurant)
    {
        $data = $request->only([
            'menu_title_color', 'menu_title_size', 'menu_title_font',
            'price_color', 'price_size', 'price_font',
            'description_color', 'description_size', 'description_font',
            'category_title_color', 'category_title_size', 'category_title_font',
            'background_color', 'header_background_color', 'primary_color', 'secondary_color',
        ]);
        $this->customization->saveForRestaurant($restaurant->id, $data);

        return redirect()->route('admin.restaurants.hub', [$restaurant, 'tab' => 'customization'])->with('success', 'Customization saved.');
    }

    private function saveHeaderFooter(Request $request, Restaurant $restaurant)
    {
        $items = $request->input('header_menu_items', '[]');
        if (is_array($items)) {
            $items = json_encode($items);
        }
        $restaurant->update([
            'header_menu_items' => $items,
            'footer_content' => $request->input('footer_content'),
            'instagram_url' => $request->input('instagram_url'),
            'facebook_url' => $request->input('facebook_url'),
            'twitter_url' => $request->input('twitter_url'),
            'whatsapp_link' => $request->input('whatsapp_link'),
            'map_latitude' => $request->input('map_latitude'),
            'map_longitude' => $request->input('map_longitude'),
        ]);

        return redirect()->route('admin.restaurants.hub', [$restaurant, 'tab' => 'header'])->with('success', 'Header & footer saved.');
    }

    private function saveCategory(Request $request, Restaurant $restaurant, string $action)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'section_id' => 'required|integer',
            'description' => 'nullable|string',
            'display_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
            'image' => 'nullable|image|max:5120',
        ]);

        $slug = Str::slug($data['name']);
        if ($action === 'update_category') {
            $category = Category::where('restaurant_id', $restaurant->id)->findOrFail($request->input('id'));
        } else {
            $category = new Category(['restaurant_id' => $restaurant->id]);
        }

        if ($request->hasFile('image')) {
            $upload = $this->uploads->storeImage($request->file('image'), 'categories');
            if ($upload['success'] ?? false) {
                $this->uploads->delete('categories', $category->image);
                $category->image = $upload['filename'];
            }
        }

        $category->fill([
            'section_id' => $data['section_id'],
            'name' => $data['name'],
            'slug' => $slug,
            'description' => $data['description'] ?? null,
            'display_order' => (int) ($data['display_order'] ?? 0),
            'is_active' => $request->boolean('is_active', true),
        ]);
        $category->save();

        $this->secondarySections->sync(
            $category->id,
            (int) $data['section_id'],
            array_map('intval', $request->input('secondary_section_ids', [])),
        );

        return redirect()->route('admin.restaurants.hub', [$restaurant, 'tab' => 'menu'])->with('success', 'Category saved.');
    }

    private function deleteCategory(Request $request, Restaurant $restaurant)
    {
        $category = Category::where('restaurant_id', $restaurant->id)->findOrFail($request->input('id'));
        $this->uploads->delete('categories', $category->image);
        MenuItem::where('category_id', $category->id)->delete();
        $category->delete();

        return redirect()->route('admin.restaurants.hub', [$restaurant, 'tab' => 'menu'])->with('success', 'Category deleted.');
    }

    private function saveMenuItem(Request $request, Restaurant $restaurant, string $action)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|integer',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'display_order' => 'nullable|integer',
            'is_available' => 'nullable|boolean',
            'image' => 'nullable|image|max:5120',
        ]);

        if ($action === 'update_menu_item') {
            $item = MenuItem::where('restaurant_id', $restaurant->id)->findOrFail($request->input('id'));
        } else {
            $item = new MenuItem(['restaurant_id' => $restaurant->id]);
        }

        if ($request->hasFile('image')) {
            $upload = $this->uploads->storeImage($request->file('image'), 'menu-items');
            if ($upload['success'] ?? false) {
                $this->uploads->delete('menu-items', $item->image);
                $item->image = $upload['filename'];
            }
        }

        $item->fill([
            'category_id' => $data['category_id'],
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
            'description' => $data['description'] ?? null,
            'price' => $data['price'],
            'display_order' => (int) ($data['display_order'] ?? 0),
            'is_available' => $request->boolean('is_available', true),
        ]);
        $item->save();

        return redirect()->route('admin.restaurants.hub', [$restaurant, 'tab' => 'menu'])->with('success', 'Menu item saved.');
    }

    private function deleteMenuItem(Request $request, Restaurant $restaurant)
    {
        $item = MenuItem::where('restaurant_id', $restaurant->id)->findOrFail($request->input('id'));
        $this->uploads->delete('menu-items', $item->image);
        $item->delete();

        return redirect()->route('admin.restaurants.hub', [$restaurant, 'tab' => 'menu'])->with('success', 'Menu item deleted.');
    }
}
