<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Container extends BaseModel
{

    const MAIN_ITEMS = 1;
    const RECOMMENDED_ITEMS = 2;

    public $table = "container";

    public $primaryKey = "idContainer";
}
