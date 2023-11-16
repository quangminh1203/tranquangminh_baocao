<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Post extends Model
{
    use HasFactory;
    protected $table = 'post';

   public function menus():HasMany {
    return $this->hasMany(Menu::class,
        'table_id')->where('type', '=', 'post');
   }
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function topic()
    {
        return $this->belongsTo(Topic::class, 'topic_id');
    }
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
