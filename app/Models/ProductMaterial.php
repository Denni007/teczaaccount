<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductMaterial extends Model
{
    protected $table = 'product_material';

    protected $fillable = [
        'product_id','material_id','quantity'
    ];

    public function raw_material()
    {
    	return $this->belongsTo('App\Models\RawMaterial','material_id','id');
    }
}
