<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    protected $table = 'receipt';
    protected $guarded = [];

    public $timestamps = true;
    public function company(){
        return $this->belongsTo('App\Models\Company', 'company_id');
    }

    public function user(){
        return $this->belongsTo('App\User', 'user_id');
    }

    public function bank()
    {
    	return $this->belongsTo('App\Models\BankAccountDetails', 'bank_id');
    }
}
