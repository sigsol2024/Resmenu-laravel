<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Manager;
use App\Models\Restaurant;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Services\UploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RestaurantController extends Controller
{
  public function index(Request $request)
  {
    $q = trim((string) $request->query('q', ''));

    $restaurants = Restaurant::query()
      ->when($q !== '', fn ($query) => $query->where('name', 'like', "%{$q}%")->orWhere('slug', 'like', "%{$q}%"))
      ->orderByDesc('id')
      ->paginate(25)
      ->withQueryString();

    return view('admin.restaurants.index', compact('restaurants', 'q'));
  }

  public function create()
  {
    return view('admin.restaurants.form', [
      'restaurant' => new Restaurant(['is_active' => true, 'template_id' => 4]),
      'manager' => null,
      'plans' => SubscriptionPlan::orderBy('display_order')->get(),
    ]);
  }

  public function store(Request $request, UploadService $uploads)
  {
    $data = $this->validated($request);
    $slug = $this->uniqueSlug($data['slug'] ?: Str::slug($data['name']));

    DB::transaction(function () use ($request, $data, $slug, $uploads) {
      $logo = $request->hasFile('logo') ? ($uploads->storeImage($request->file('logo'), 'logos')['filename'] ?? null) : null;
      $hero = $request->hasFile('hero_image') ? ($uploads->storeImage($request->file('hero_image'), 'heroes')['filename'] ?? null) : null;

      $restaurant = Restaurant::create([
        'name' => $data['name'],
        'slug' => $slug,
        'email' => $data['manager_email'],
        'phone' => $data['phone'] ?? null,
        'address' => $data['address'] ?? null,
        'description' => $data['description'] ?? null,
        'logo' => $logo,
        'hero_image' => $hero,
        'template_id' => $data['template_id'] ?? 4,
        'is_active' => $request->boolean('is_active', true),
      ]);

      Manager::create([
        'username' => $data['manager_username'],
        'email' => $data['manager_email'],
        'password_hash' => Hash::make($data['manager_password']),
        'restaurant_id' => $restaurant->id,
      ]);

      if (! empty($data['plan_id'])) {
        Subscription::create([
          'restaurant_id' => $restaurant->id,
          'plan_id' => $data['plan_id'],
          'billing_cycle' => 'monthly',
          'status' => 'trial',
          'trial_ends_at' => now()->addDays(14),
        ]);
      }
    });

    return redirect()->route('admin.restaurants.index')->with('success', 'Restaurant created.');
  }

  public function show(Restaurant $restaurant)
  {
    $restaurant->load(['sections', 'categories']);
    $subscription = Subscription::query()->where('restaurant_id', $restaurant->id)->orderByDesc('id')->with('plan')->first();
    $manager = Manager::where('restaurant_id', $restaurant->id)->first();

    $stats = [
      'sections' => $restaurant->sections->count(),
      'categories' => $restaurant->categories->count(),
      'menu_items' => (int) DB::table('menu_items')->where('restaurant_id', $restaurant->id)->count(),
      'orders' => (int) DB::table('orders')->where('restaurant_id', $restaurant->id)->count(),
      'reservations' => (int) DB::table('table_reservations')->where('restaurant_id', $restaurant->id)->count(),
    ];

    $customization = DB::table('customization_settings')
      ->where('restaurant_id', $restaurant->id)
      ->where('template_id', $restaurant->template_id)
      ->first();

    return view('admin.restaurants.show', compact('restaurant', 'subscription', 'manager', 'stats', 'customization'));
  }

  public function edit(Restaurant $restaurant)
  {
    $manager = Manager::where('restaurant_id', $restaurant->id)->first();

    return view('admin.restaurants.form', [
      'restaurant' => $restaurant,
      'manager' => $manager,
      'plans' => SubscriptionPlan::orderBy('display_order')->get(),
    ]);
  }

  public function update(Request $request, Restaurant $restaurant, UploadService $uploads)
  {
    $data = $this->validated($request, $restaurant->id);
    $slug = $this->uniqueSlug($data['slug'] ?: Str::slug($data['name']), $restaurant->id);

    $payload = [
      'name' => $data['name'],
      'slug' => $slug,
      'email' => $data['manager_email'],
      'phone' => $data['phone'] ?? null,
      'address' => $data['address'] ?? null,
      'description' => $data['description'] ?? null,
      'template_id' => $data['template_id'] ?? $restaurant->template_id,
      'is_active' => $request->boolean('is_active'),
    ];

    if ($request->hasFile('logo')) {
      $result = $uploads->storeImage($request->file('logo'), 'logos');
      if ($result['success'] ?? false) {
        $uploads->delete('logos', $restaurant->logo);
        $payload['logo'] = $result['filename'];
      }
    }
    if ($request->hasFile('hero_image')) {
      $result = $uploads->storeImage($request->file('hero_image'), 'heroes');
      if ($result['success'] ?? false) {
        $uploads->delete('heroes', $restaurant->hero_image);
        $payload['hero_image'] = $result['filename'];
      }
    }

    $restaurant->update($payload);

    if (! empty($data['manager_email'])) {
      Manager::where('restaurant_id', $restaurant->id)->update([
        'email' => $data['manager_email'],
        'username' => $data['manager_username'] ?? Manager::where('restaurant_id', $restaurant->id)->value('username'),
      ]);
    }

    if (! empty($data['manager_password'])) {
      Manager::where('restaurant_id', $restaurant->id)->update([
        'password_hash' => Hash::make($data['manager_password']),
      ]);
    }

    return redirect()->route('admin.restaurants.show', $restaurant)->with('success', 'Restaurant updated.');
  }

  public function destroy(Restaurant $restaurant, UploadService $uploads)
  {
    $uploads->delete('logos', $restaurant->logo);
    $uploads->delete('heroes', $restaurant->hero_image);
    $restaurant->delete();

    return redirect()->route('admin.restaurants.index')->with('success', 'Restaurant deleted.');
  }

  private function validated(Request $request, ?int $ignoreId = null): array
  {
    return $request->validate([
      'name' => 'required|string|max:255',
      'slug' => 'nullable|string|max:255',
      'description' => 'nullable|string',
      'phone' => 'nullable|string|max:50',
      'address' => 'nullable|string',
      'template_id' => 'nullable|integer|min:1',
      'plan_id' => 'nullable|integer|exists:subscription_plans,id',
      'manager_username' => 'required|string|max:100',
      'manager_email' => 'required|email|max:255',
      'manager_password' => $ignoreId ? 'nullable|string|min:'.config('resmenu.password_min_length', 8) : 'required|string|min:'.config('resmenu.password_min_length', 8),
      'is_active' => 'nullable|boolean',
    ]);
  }

  private function uniqueSlug(string $slug, ?int $ignoreId = null): string
  {
    $base = Str::slug($slug) ?: 'restaurant';
    $candidate = $base;
    $i = 0;
    while (Restaurant::where('slug', $candidate)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
      $candidate = $base.'-'.(++$i);
    }

    return $candidate;
  }
}
