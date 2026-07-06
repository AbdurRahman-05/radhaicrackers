<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\StockImage;
use Illuminate\Support\Facades\Storage;


class StockImageUpload extends Component
{
    use WithFileUploads;

    public $stockId;
    public $uploadedImages = [];
    public $showDelete = true;

    protected $rules = [
        'uploadedImages.*' => 'image|max:2048',
    ];

    public function mount($stockId = null)
    {
        $this->stockId = $stockId ?? request()->query('stock_id');
    }

    public function uploadImages()
    {
        if (!$this->stockId) {
            session()->flash('error', 'No stock selected.');
            return;
        }
        $this->validate();
        foreach ($this->uploadedImages as $image) {
            $path = $image->store('stocks', 'public');
            StockImage::create([
                'stock_id' => $this->stockId,
                'image_path' => 'storage/' . $path,
            ]);
        }
        $this->uploadedImages = [];
        session()->flash('success', 'Images uploaded successfully!');
    }

    public function deleteImage($id)
    {
        $img = StockImage::findOrFail($id);
        Storage::delete(str_replace('storage/', 'public/', $img->image_path));
        $img->delete();
    }

    public function render()
    {
        $images = StockImage::where('stock_id', $this->stockId)->get();
        return view('livewire.stock-image-upload', [
            'images' => $images,
            'showDelete' => $this->showDelete,
        ])->layout('layouts.admin');
    }
}
