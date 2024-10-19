<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function admins(): BelongsToMany
    {
        return $this->belongsToMany(Admin::class, 'department_admin', 'department_id', 'admin_id');
    }
}
