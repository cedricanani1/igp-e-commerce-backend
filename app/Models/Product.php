<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory,SoftDeletes;
    protected $guarded = [
        'id',
    ];

    public function type(){
        return $this->belongsTo(ProductType::class,'product_type_id');
    }
    public function rate(){
        return $this->hasMany(ProductRate::class,'product_id');
    }
    public function order(){
        return $this->belongsToMany(Order::class,'order_products','product_id','order_id');
    }
}
