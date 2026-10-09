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

        return asset('storage/' . $cleanPath);
    }
}
