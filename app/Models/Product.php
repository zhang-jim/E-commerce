<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [ //定義可進行質量賦值的屬性
        'name',
        'description',
        'price',
        'inventory',
        'image',
    ];
    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_products');
    }
}
