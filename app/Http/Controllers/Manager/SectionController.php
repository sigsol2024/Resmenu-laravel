<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Models\Section;
use App\Services\DisplayOrderService;
use App\Services\UploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SectionController extends Controller
{
    public function __construct(
        private UploadService $uploads,
        private DisplayOrderService $displayOrders,
    ) {}

    public function index(Request $request)
    {
        $restaurantId = (int) $request->attributes->get('restaurant_id');

        $sections = Section::query()
            ->where('restaurant_id', $restaurantId)
            ->withCount('categories')
            ->orderBy('display_order')
            ->orderBy('name')
            ->get();

        $editSection = null;
        if ($request->filled('edit')) {
            $editSection = Section::query()
                ->where('restaurant_id', $restaurantId)
                ->where('id', $request->integer('edit'))
                ->first();
        }

        return view('manager.sections.index', [
            'sections' => $sections,
            'restaurant' => Restaurant::findOrFail($restaurantId),
            'uploadUrl' => rtrim(config('resmenu.canonical_upload_url') ?: config('resmenu.upload_url'), '/'),
            'editSection' => $editSection,
            'openCreateModal' => $request->query('open') === 'create',
            'nextDisplayOrder' => $this->displayOrders->nextSectionOrder($restaurantId),
        ]);
    }

    public function create(Request $request)
    {
        return redirect()->route('manager.sections.index', ['open' => 'create']);
    }

    public function store(Request $request)
    {
        $restaurantId = (int) $request->attributes->get('restaurant_id');
        $data = $this->validated($request, $restaurantId);

        if ($request->hasFile('image')) {
            $upload = $this->uploads->storeImage($request->file('image'), 'sections');
            if (! $upload['success']) {
                return back()->withErrors(['image' => $upload['message']])->withInput();
            }
            $data['image'] = $upload['filename'];
        }

        Section::create($data);

        return redirect()->route('manager.sections.index')->with('success', 'Section created.');
    }

    public function edit(Request $request, Section $section)
    {
        $this->authorizeRestaurant($request, $section);

        return redirect()->route('manager.sections.index', ['edit' => $section->id]);
    }

    public function update(Request $request, Section $section)
    {
        $this->authorizeRestaurant($request, $section);
        $data = $this->validated($request, (int) $section->restaurant_id, $section);

        if ($request->hasFile('image')) {
            $upload = $this->uploads->storeImage($request->file('image'), 'sections');
            if (! $upload['success']) {
                return back()->withErrors(['image' => $upload['message']])->withInput();
            }
            $this->uploads->delete('sections', $section->image);
            $data['image'] = $upload['filename'];
        } elseif ($request->boolean('remove_image') && $section->image) {
            $this->uploads->delete('sections', $section->image);
            $data['image'] = null;
        }

        $section->update($data);

        return redirect()->route('manager.sections.index')->with('success', 'Section updated.');
    }

    public function destroy(Request $request, Section $section)
    {
        $this->authorizeRestaurant($request, $section);
        $this->uploads->delete('sections', $section->image);
        $section->delete();

        return redirect()->route('manager.sections.index')->with('success', 'Section deleted.');
    }

    private function validated(Request $request, int $restaurantId, ?Section $existing = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'display_order' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
            'image' => ['nullable', 'image', 'max:5120'],
            'remove_image' => ['nullable', 'boolean'],
        ]);

        $ignoreId = $existing?->id;
        $slug = Str::slug($data['name']);
        if (Section::query()->where('restaurant_id', $restaurantId)->where('slug', $slug)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug .= '-'.Str::random(4);
        }

        $order = $request->input('display_order');
        if ($order === null || $order === '') {
            $order = $existing
                ? (int) $existing->display_order
                : $this->displayOrders->nextSectionOrder($restaurantId);
        }

        return [
            'restaurant_id' => $restaurantId,
            'name' => $data['name'],
            'slug' => $slug,
            'display_order' => (int) $order,
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
