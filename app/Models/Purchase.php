<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $table = 'purchase';
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
    	return $this->hasMany('App\Models\PurchaseItems','purchase_id','id');
    }
}
