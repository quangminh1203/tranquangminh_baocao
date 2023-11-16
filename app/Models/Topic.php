<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    use HasFactory;
    protected $table = 'topic';
    public function posts()
    {
        return $this->hasMany(Post::class);
    }
    public function menus()
    {
        return $this->hasMany(Menu::class, 'table_id')->where('type', '=', 'topic');
    }
}
