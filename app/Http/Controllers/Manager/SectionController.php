<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Models\Section;
use App\Services\UploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SectionController extends Controller
{
    public function __construct(private UploadService $uploads) {}

    public function index(Request $request)
    {
        $restaurantId = (int) $request->attributes->get('restaurant_id');

        $sections = Section::query()
            ->where('restaurant_id', $restaurantId)
            ->withCount('categories')
            ->orderBy('display_order')
            ->get();

        return view('manager.sections.index', [
            'sections' => $sections,
            'restaurant' => Restaurant::findOrFail($restaurantId),
        ]);
    }

    public function create(Request $request)
    {
        $restaurantId = (int) $request->attributes->get('restaurant_id');

        return view('manager.sections.form', [
            'section' => new Section(['is_active' => true, 'display_order' => 0]),
            'restaurant' => Restaurant::findOrFail($restaurantId),
        ]);
    }

    public function store(Request $request)
    {
        $restaurantId = (int) $request->attributes->get('restaurant_id');
        $data = $this->validated($request, $restaurantId);
        Section::create($data);

        return redirect()->route('manager.sections.index')->with('success', 'Section created.');
    }

    public function edit(Request $request, Section $section)
    {
        $this->authorizeRestaurant($request, $section);

        return view('manager.sections.form', [
            'section' => $section,
            'restaurant' => Restaurant::findOrFail((int) $request->attributes->get('restaurant_id')),
        ]);
    }

    public function update(Request $request, Section $section)
    {
        $this->authorizeRestaurant($request, $section);
        $section->update($this->validated($request, (int) $section->restaurant_id, $section->id));

        return redirect()->route('manager.sections.index')->with('success', 'Section updated.');
    }

    public function destroy(Request $request, Section $section)
    {
        $this->authorizeRestaurant($request, $section);
        $section->delete();

        return redirect()->route('manager.sections.index')->with('success', 'Section deleted.');
    }

    private function validated(Request $request, int $restaurantId, ?int $ignoreId = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'display_order' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $slug = Str::slug($data['name']);
        if (Section::query()->where('restaurant_id', $restaurantId)->where('slug', $slug)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug .= '-'.Str::random(4);
        }

        return [
            'restaurant_id' => $restaurantId,
            'name' => $data['name'],
            'slug' => $slug,
            'display_order' => (int) ($data['display_order'] ?? 0),
            'is_active' => $request->boolean('is_active', true),
        ];
    }

    private function authorizeRestaurant(Request $request, Section $section): void
    {
        if ((int) $section->restaurant_id !== (int) $request->attributes->get('restaurant_id')) {
            abort(403);
        }
    }
}
