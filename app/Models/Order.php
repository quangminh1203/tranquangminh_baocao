<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;

class Order extends Model
{
    use HasFactory;
    protected $table = 'order';
    public function orderdetail()
    {
        return $this->hasMany(Orderdetail::class);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    
}
