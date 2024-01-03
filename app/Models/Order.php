<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['order_number', 'member_id', 'order_amount', 'notes'];
    use HasFactory;
    public function member(){
        return $this->belongsTo(Member::class);
    }
    public function products(){
        return $this->belongsToMany(Product::class, 'order_products')->withPivot('created_at', 'updated_at');;
    }
}