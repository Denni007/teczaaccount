<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $table = 'invoice';
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

    public function perfoma_invoice()
    {
    	return $this->belongsTo('App\Models\PerfomaInvoice','pi_id');
    }
    public function invoice_items()
    {
    	return $this->hasMany('App\Models\InvoiceItems','invoice_id','id');
    }
}
