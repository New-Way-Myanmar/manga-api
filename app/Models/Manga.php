<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Manga extends Model
{
    use HasFactory;

    protected $table = 'mangas';

    protected $fillable = [

      'name',
      'about',
      'slug',
      'imagePath',
      'status',
      'author',
      'releaseDate',
      'altName',
      'created_by',
      'views'
    ];

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function user(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_manga', 'manga_id', 'user_id');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'manga_category', 'manga_id', 'category_id');
    }

    public function chapters(): HasMany
    {
        return $this->hasMany(Chapter::class, 'manga_id');
    }

    public function characters(): HasMany
    {
        return $this->hasMany(Character::class, 'manga_id');

    }
}
