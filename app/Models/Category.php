<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends BaseModel
{
    public $table = "category";

    public $primaryKey = "idCategory";

    protected $fillable = [
        'name',
        'description',
    ];
}
