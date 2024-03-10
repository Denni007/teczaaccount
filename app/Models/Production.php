<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Production extends Model
{
    protected $table = 'production';

    protected $fillable = [
        'company_id', 'product_id', 'quantity', 'weight', 'batch_no', 'certified','batch_unique_id'
    ];

    public function product(){
        return $this->belongsTo('App\Models\Product', 'product_id','id');
    }

    // public function production_material()
    // {
    // 	return $this->hasMany('App\Models\Product');
    // }
}
