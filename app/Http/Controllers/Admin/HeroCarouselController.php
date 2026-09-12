<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroSlide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class HeroCarouselController extends Controller
{
    /**
     * Display a listing of carousel slides.
     */
    public function index()
    {
        $slides = HeroSlide::orderBy('sort_order', 'asc')->orderBy('id', 'desc')->get();
        return view('admin.hero_carousel.index', compact('slides'));
    }

    /**
     * Show the form for creating a new carousel slide.
     */
    public function create()
    {
        // Find preset images available in radhe_crackers_images_2026 and images
        $presetImages = [];
        $bannerDir = public_path('images/radhe_crackers_images_2026');
        if (File::isDirectory($bannerDir)) {
            foreach (File::files($bannerDir) as $file) {
                $presetImages[] = 'images/radhe_crackers_images_2026/' . $file->getFilename();
            }
        }

        return view('admin.hero_carousel.create', compact('presetImages'));
    }

    /**
     * Store a newly created carousel slide in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:500',
            'link_url' => 'nullable|string|max:255',
            'button_text' => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer',
            'image_file' => 'nullable|image|max:10240', // up to 10MB
            'preset_image' => 'nullable|string',
        ]);

        $imagePath = 'images/radhe_crackers_images_2026/home carosel 1.png';

        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
            
            // Ensure target directories exist
            $targetDir = public_path('storage/hero_carousel');
            $storageTargetDir = storage_path('app/public/hero_carousel');
            if (!File::isDirectory($targetDir)) {
                File::makeDirectory($targetDir, 0755, true);
            }
            if (!File::isDirectory($storageTargetDir)) {
                File::makeDirectory($storageTargetDir, 0755, true);
            }

            // Save to storage
            $file->move($targetDir, $filename);
            @copy($targetDir . '/' . $filename, $storageTargetDir . '/' . $filename);

            $imagePath = 'hero_carousel/' . $filename;
        } elseif (!empty($validated['preset_image'])) {
            $imagePath = $validated['preset_image'];
        }

        $slide = new HeroSlide();
        $slide->title = $validated['title'] ?? '';
        $slide->subtitle = $validated['subtitle'] ?? '';
        $slide->link_url = $validated['link_url'] ?? '/quotation';
        $slide->button_text = $validated['button_text'] ?? 'Order Now';
        $slide->sort_order = $validated['sort_order'] ?? (HeroSlide::max('sort_order') + 1);
        $slide->is_active = $request->boolean('is_active', true);
        $slide->image = $imagePath;
        $slide->save();

        return redirect()->route('admin.hero_carousel.index')->with('success', 'Hero Carousel slide added successfully.');
    }

    /**
     * Show the form for editing the specified carousel slide.
     */
    public function edit($id)
    {
        $slide = HeroSlide::findOrFail($id);

        $presetImages = [];
        $bannerDir = public_path('images/radhe_crackers_images_2026');
        if (File::isDirectory($bannerDir)) {
            foreach (File::files($bannerDir) as $file) {
                $presetImages[] = 'images/radhe_crackers_images_2026/' . $file->getFilename();
            }
        }

        return view('admin.hero_carousel.edit', compact('slide', 'presetImages'));
    }

    /**
     * Update the specified carousel slide in storage.
     */
    public function update(Request $request, $id)
    {
        $slide = HeroSlide::findOrFail($id);

        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:500',
            'link_url' => 'nullable|string|max:255',
            'button_text' => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer',
            'image_file' => 'nullable|image|max:10240',
            'preset_image' => 'nullable|string',
        ]);

        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());

            $targetDir = public_path('storage/hero_carousel');
            $storageTargetDir = storage_path('app/public/hero_carousel');
            if (!File::isDirectory($targetDir)) {
                File::makeDirectory($targetDir, 0755, true);
            }
            if (!File::isDirectory($storageTargetDir)) {
                File::makeDirectory($storageTargetDir, 0755, true);
            }

            $file->move($targetDir, $filename);
            @copy($targetDir . '/' . $filename, $storageTargetDir . '/' . $filename);

            $slide->image = 'hero_carousel/' . $filename;
        } elseif (!empty($validated['preset_image'])) {
            $slide->image = $validated['preset_image'];
        }

        $slide->title = $validated['title'] ?? '';
        $slide->subtitle = $validated['subtitle'] ?? '';
        $slide->link_url = $validated['link_url'] ?? '/quotation';
        $slide->button_text = $validated['button_text'] ?? 'Order Now';
        $slide->sort_order = $validated['sort_order'] ?? 0;
        $slide->is_active = $request->boolean('is_active');
        $slide->save();

        return redirect()->route('admin.hero_carousel.index')->with('success', 'Hero Carousel slide updated successfully.');
    }

    /**
     * Remove the specified carousel slide from storage.
     */
    public function destroy($id)
    {
        $slide = HeroSlide::findOrFail($id);
        $slide->delete();

        return redirect()->route('admin.hero_carousel.index')->with('success', 'Hero Carousel slide deleted successfully.');
    }

    /**
     * Toggle active state of a slide.
     */
    public function toggle($id)
    {
        $slide = HeroSlide::findOrFail($id);
        $slide->is_active = !$slide->is_active;
        $slide->save();

        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'is_active' => $slide->is_active]);
        }

        return redirect()->back()->with('success', 'Slide status toggled successfully.');
    }
}
