<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'product';

    protected $fillable = [
        'company_id', 'product_name', 'product_type', 'weight', 'certified','gst_percentage'
    ];

    public function type(){
        return $this->belongsTo('App\Models\ProductType', 'product_type','id');
    }

    public function product_material()
    {
    	return $this->hasMany('App\Models\ProductMaterial','product_id','id');
    }
}
