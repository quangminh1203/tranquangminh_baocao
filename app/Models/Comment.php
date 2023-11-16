<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    protected $table = 'comment';
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class, 'table_id');
    }
    public function post()
    {
        return $this->belongsTo(Post::class, 'table_id');
    }
    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id', 'id')->with('replies');
    }
    public function repliedBy()
    {
        return $this->belongsTo(User::class, 'reply_id');
    }
}
