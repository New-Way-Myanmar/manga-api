<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Chapter extends Model
{
    use HasFactory;

    protected $fillable = [
      'manga_id',
      'slug',
      'season',
      'name',
      'imagePath',
      'price',
      'created_by'
    ];

//    protected $hidden = [
//      'created_by',
//    ];

    protected $casts = [
        'imagePath' => 'array',
    ];

    public function manga(): BelongsTo
    {
        return $this->belongsTo(Manga::class, 'manga_id');
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }
}
