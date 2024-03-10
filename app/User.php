<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'address','phone','user_type','bank_name','ifsc_code','swift_code','beneficary_name','account_no','account_type','branch_name','designation','wallet_unique_id','total_wallet','type','is_active','shift_type','text_pass','created_by','company_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function company(){
        return $this->belongsTo('App\Models\Company', 'company_id','id');
    }

    public function permission(){
        return $this->hasMany('App\Models\Permissions', 'user_id','id');
    }
}
