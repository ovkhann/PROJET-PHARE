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

    /** Accesseur pour obtenir l'URL complète de l'image */
    public function getPictureUrlAttribute()
    {
        return $this->picture ? asset('storage/' . $this->picture) : null;
    }
}
