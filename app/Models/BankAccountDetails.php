<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankAccountDetails extends Model
{
    protected $guarded = [];

    public $timestamps = true;

    public function company(){
        return $this->belongsTo('App\Models\Company', 'company_id');
    }
}
