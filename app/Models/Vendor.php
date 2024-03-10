<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vendor extends Model
{
    use SoftDeletes;

    protected $table = 'vendor';

    protected $guarded = [];

    public $timestamps = true;
    protected $appends = ['country_name', 'state_name'];

    public function bank_account()
    {
        return $this->hasMany("App\Models\BankAccountDetails", 'vendor_id', 'id');
    }

    public function country()
    {
    	return $this->belongsTo("App\Models\Country", 'country_id');
    }

    public function state()
    {
    	return $this->belongsTo("App\Models\State", 'state_id');
    }

    public function getCountryNameAttribute()
    {
        $result = Country::where('id', $this->attributes['country_id']);
        return @$result->first()->name;
    }

    public function getStateNameAttribute()
    {
        $result = State::where('id', $this->attributes['state_id']);
        return @$result->first()->name;
    }
}
