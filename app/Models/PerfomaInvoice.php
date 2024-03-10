<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerfomaInvoice extends Model
{
    protected $table = 'pi_invoice';
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

    public function bank_account()
    {
    	return $this->belongsTo('App\Models\BankAccountDetails','bank_id');
    }
    public function pi_items()
    {
    	return $this->hasMany('App\Models\PiItems','pi_id','id');
    }
}
