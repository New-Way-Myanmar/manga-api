<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_id',
        'code',
        'ammount',
        'expired_at'
    ];

    protected $hidden = [
        'created_at',
        'expried_at',
    ];

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_coupon', 'coupon_id', 'user_id');
    }
}
