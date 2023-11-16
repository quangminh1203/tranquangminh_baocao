<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;
    protected $table ='brand';

    public function products()
    {
        return $this->hasMany(Product::class);
    }
    public function menus()
    {
        return $this->hasMany(Menu::class, 'table_id')->where('type', '=', 'brand');
    }
    
    
}
