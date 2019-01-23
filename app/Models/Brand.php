<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends BaseModel
{
    public $table = "brand";

    public $primaryKey = "idBrand";

    protected $fillable = [
        'name',
        'description',
    ];
}
