<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{

    public $timestamps = false;


    use HasFactory;
    protected $fillable = [
        'date',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_products');
    }


    public function users()
    {
        return $this->belongsTo(Product::class, 'users');
    }
}