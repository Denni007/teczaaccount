<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permissions extends Model
{
    protected $table = 'user_permissions';

    protected $fillable = [
        'user_id', 'permission', 'status'
    ];
}
