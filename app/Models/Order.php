<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    public function member(){
        return $this->belongsTo(Member::class);
    }
    public function products(){
        return $this->belongsToMany(Product::class, 'order_products');
    }
}

// class OrderProduct extends Model
// {
//     use HasFactory;
// }