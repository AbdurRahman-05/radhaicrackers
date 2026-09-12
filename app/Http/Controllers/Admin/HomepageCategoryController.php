<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HomepageCategory;
use Illuminate\Support\Facades\File;

class HomepageCategoryController extends Controller
{
    /**
     * Display listing of homepage category cards
     */
    public function index()
    {
        HomepageCategory::createTableIfNotExists();
        $categories = HomepageCategory::orderBy('sort_order', 'asc')->get();
        $availableImages = HomepageCategory::getAvailable2026Images();

        return view('admin.homepage_categories.index', compact('categories', 'availableImages'));
    }

    /**
     * Show form to create a new category card
     */
    public function create()
    {
        HomepageCategory::createTableIfNotExists();
        $availableImages = HomepageCategory::getAvailable2026Images();
        $nextOrder = (HomepageCategory::max('sort_order') ?? 0) + 1;

        return view('admin.homepage_categories.create', compact('availableImages', 'nextOrder'));
    }

    /**
     * Store newly created category card
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'search_term' => 'nullable|string|max:255',
            'product_count' => 'nullable|integer|min:0',
            'auto_count' => 'nullable|boolean',
            'sort_order' => 'nullable|integer',
            'selected_image' => 'nullable|string',
            'image' => 'nullable|image|max:5120',
        ]);

        $imagePath = 'images/radhe_crackers_images_2026/single flash.png';

        // Check if an image was uploaded
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());

            $targetDir = public_path('storage/homepage_categories');
            $storageTargetDir = storage_path('app/public/homepage_categories');
            if (!File::isDirectory($targetDir)) {
                File::makeDirectory($targetDir, 0755, true);
            }
            if (!File::isDirectory($storageTargetDir)) {
                File::makeDirectory($storageTargetDir, 0755, true);
            }

            $file->move($targetDir, $filename);
            @copy($targetDir . '/' . $filename, $storageTargetDir . '/' . $filename);

            $imagePath = 'homepage_categories/' . $filename;
        } elseif (!empty($validated['selected_image'])) {
            $imagePath = $validated['selected_image'];
        }

        $category = new HomepageCategory();
        $category->name = $validated['name'];
        $category->search_term = $validated['search_term'] ?: $validated['name'];
        $category->image = $imagePath;
        $category->product_count = $validated['product_count'] ?? 10;
        $category->auto_count = $request->boolean('auto_count');
        $category->sort_order = $validated['sort_order'] ?? ((HomepageCategory::max('sort_order') ?? 0) + 1);
        $category->is_active = $request->boolean('is_active', true);
        $category->save();

        return redirect()->route('admin.homepage_categories.index')
            ->with('success', 'Category card created successfully with current year image.');
    }

    /**
     * Show edit form for category card
     */
    public function edit($id)
    {
        HomepageCategory::createTableIfNotExists();
        $category = HomepageCategory::findOrFail($id);
        $availableImages = HomepageCategory::getAvailable2026Images();

        return view('admin.homepage_categories.edit', compact('category', 'availableImages'));
    }

    /**
     * Update category card
     */
    public function update(Request $request, $id)
    {
        $category = HomepageCategory::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'search_term' => 'nullable|string|max:255',
            'product_count' => 'nullable|integer|min:0',
            'auto_count' => 'nullable|boolean',
            'sort_order' => 'nullable|integer',
            'selected_image' => 'nullable|string',
            'image' => 'nullable|image|max:5120',
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());

            $targetDir = public_path('storage/homepage_categories');
            $storageTargetDir = storage_path('app/public/homepage_categories');
            if (!File::isDirectory($targetDir)) {
                File::makeDirectory($targetDir, 0755, true);
            }
            if (!File::isDirectory($storageTargetDir)) {
                File::makeDirectory($storageTargetDir, 0755, true);
            }

            $file->move($targetDir, $filename);
            @copy($targetDir . '/' . $filename, $storageTargetDir . '/' . $filename);

            $category->image = 'homepage_categories/' . $filename;
        } elseif (!empty($validated['selected_image'])) {
            $category->image = $validated['selected_image'];
        }

        $category->name = $validated['name'];
        $category->search_term = $validated['search_term'] ?: $validated['name'];
        $category->product_count = $validated['product_count'] ?? 10;
        $category->auto_count = $request->boolean('auto_count');
        $category->sort_order = $validated['sort_order'] ?? 0;
        $category->is_active = $request->boolean('is_active', true);
        $category->save();

        return redirect()->route('admin.homepage_categories.index')
            ->with('success', 'Category card updated successfully with current year image.');
    }

    /**
     * Toggle active status
     */
    public function toggle($id)
    {
        $category = HomepageCategory::findOrFail($id);
        $category->is_active = !$category->is_active;
        $category->save();

        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'is_active' => $category->is_active]);
        }

        return redirect()->back()->with('success', 'Category active status updated.');
    }

    /**
     * Remove category card
     */
    public function destroy($id)
    {
        $category = HomepageCategory::findOrFail($id);
        $category->delete();

        return redirect()->route('admin.homepage_categories.index')
            ->with('success', 'Category card deleted.');
    }

    /**
     * Reset to standard 7 2026 categories
     */
    public function resetDefaults()
    {
        HomepageCategory::seedDefaults();

        return redirect()->route('admin.homepage_categories.index')
            ->with('success', 'Homepage categories have been reset to current 2026 images and settings.');
    }
}
