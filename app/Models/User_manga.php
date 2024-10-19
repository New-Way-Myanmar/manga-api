<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class user_manga extends Model
{
    use HasFactory;

    protected $table = "user_manga";

    protected $fillable = [
        
        'user_id',
        'manga_id'        
    ];
}
