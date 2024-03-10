<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductionMaterial extends Model
{
    protected $table = 'production_material';

    protected $fillable = [
        'production_id','material_id','quantity'
    ];
}
