<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    public $timestamps = false;

    use HasFactory;
    protected $fillable = [
        'size',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}
