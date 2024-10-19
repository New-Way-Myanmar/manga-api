<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'logo',
        'url',
        'partner_date',
        'leader_name',
        'leader_email',
        'leader_phone',
    ];

    protected $casts = [
        'partner_date' => 'datetime',
    ];
}
