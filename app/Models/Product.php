<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Product extends Model
{
    use HasFactory;
    protected $table = 'product';

    public function sale(): HasOne
    {
        return $this->hasOne(ProductSale::class);
    }
    public function store(): HasOne
    {
        return $this->hasOne(ProductStore::class);
    }
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class,'category_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($product) {
            foreach ($product->images as $image) {
                $imagePath = public_path("images/product/" . $image->image);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
                $image->delete();
            }
        });
    }

    public function orderdetail(): HasMany
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
