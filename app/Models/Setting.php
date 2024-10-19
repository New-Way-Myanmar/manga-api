<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'logo',
        'favicon',
        'site_name',
        'site_description',
        'site_about',
        'site_keywords',
        'site_email',
        'site_phone',
        'site_address',
        'site_fb',
        'site_twitter',
        'site_instagram',
        'site_linkedin',
        'site_youtube',
    ];
    
}
