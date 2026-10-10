<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockImage extends Model
{
    protected $fillable = ['stock_id', 'image_path'];
    protected $appends = ['image_url'];

    public function stock()
    {
        return $this->belongsTo(Stock::class);
    }

    public function getImageUrlAttribute()
    {
        if (empty($this->image_path)) {
            return null;
        }

        if (filter_var($this->image_path, FILTER_VALIDATE_URL)) {
            return $this->image_path;
        }

        $cleanPath = ltrim($this->image_path, '/');

        // Strip duplicate storage or public prefixes
        if (str_starts_with($cleanPath, 'public/storage/')) {
            $cleanPath = substr($cleanPath, 15);
        } elseif (str_starts_with($cleanPath, 'storage/')) {
            $cleanPath = substr($cleanPath, 8);
        } elseif (str_starts_with($cleanPath, 'public/')) {
            $cleanPath = substr($cleanPath, 7);
        }

        $filename = basename($cleanPath);

        // If path is a bare filename without folder, prepend stocks/
        if (!str_contains($cleanPath, '/')) {
            $cleanPath = 'stocks/' . $cleanPath;
        }

        Stock::syncUploadedFile($cleanPath);

        // Check if physical file exists in stocks folder or direct path
        if (file_exists(public_path('storage/' . $cleanPath)) || file_exists(storage_path('app/public/' . $cleanPath))) {
            return url('storage/' . $cleanPath);
        }

        if (file_exists(public_path('storage/stocks/' . $filename)) || file_exists(storage_path('app/public/stocks/' . $filename))) {
            return url('storage/stocks/' . $filename);
        }

        if (file_exists(public_path('stocks/' . $filename))) {
            return url('stocks/' . $filename);
        }

        if (file_exists(public_path('storage/' . $filename))) {
            return url('storage/' . $filename);
        }

        if (file_exists(public_path($filename))) {
            return url($filename);
        }

        if (file_exists(base_path('public/storage/stocks/' . $filename))) {
            return url('storage/stocks/' . $filename);
        }

        if (file_exists(base_path('public/' . $filename))) {
            return url($filename);
        }

        return url('storage/' . $cleanPath);
    }
}
