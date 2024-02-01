<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;
    protected $fillable = [ //定義可進行質量賦值的屬性
        'name',
        'username',
        'password',
        'email',
    ];
    public function orders(){
        return $this->hasMany(Order::class);
    }
}
