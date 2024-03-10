<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PiItems extends Model
{
    protected $table = 'pi_invoice_item';
    protected $guarded = [];

    public $timestamps = true;

    public function product(){
        return $this->belongsTo('App\Models\Product', 'product_id','id');
    }
}
