<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Product;

class Cart extends Model
{
    use HasFactory;
    protected $table = "cart";
    protected $fillable = [
        'user_id', 'product_id', 'product_qty'
    ];
    public $timestamps = false;
    public function products()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
