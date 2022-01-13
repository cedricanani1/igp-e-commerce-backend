<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductType extends Model
{
    use HasFactory,SoftDeletes;
    protected $guarded = [
        'id',
    ];
    public function products(){
        return $this->hasMany(Product::class,'product_type_id');
    }

    public function child(){
        return $this->hasMany(ProductType::class,'parent_id');
    }
    public function parent(){
        return $this->belongsTo(ProductType::class,'parent_id');
    }
}
