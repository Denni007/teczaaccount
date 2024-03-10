<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    protected $table = 'po_invoice';
    protected $guarded = [];

    public $timestamps = true;
    public function company(){
        return $this->belongsTo('App\Models\Company', 'company_id');
    }

    public function user(){
        return $this->belongsTo('App\User', 'user_id');
    }

    public function vendor(){
        return $this->belongsTo('App\Models\Vendor', 'vendor_id');
    }

    public function po_items()
    {
    	return $this->hasMany('App\Models\POItems','po_id','id');
    }
}