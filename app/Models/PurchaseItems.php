<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseItems extends Model
{
    protected $table = 'purchase_items';
    protected $guarded = [];

    public $timestamps = true;
    public function material(){
        return $this->belongsTo('App\Models\RawMaterial', 'product_id','id');
    }
    public function otherproduct(){
        return $this->belongsTo('App\Models\OtherProduct', 'product_id','id');
    }
}
