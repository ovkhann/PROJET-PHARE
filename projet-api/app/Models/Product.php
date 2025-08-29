<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'stock',
        'picture',
        'description',
        'archived',
        'category_id',
    ];

    // Cast JSON pour que picture devienne un tableau
    protected $casts = [
        'picture' => 'array',
    ];

    /** Relation avec la catégorie */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /** Relation avec les options (plusieurs) */
    public function options()
    {
        return $this->belongsToMany(Option::class);
    }

    /** Accesseur pour obtenir les URL complètes des images */
    public function getPictureUrlAttribute()
    {
        if (!$this->picture) return [];

        return array_map(fn($img) => asset('storage/' . $img), $this->picture);
    }
}
