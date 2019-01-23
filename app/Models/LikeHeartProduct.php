<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LikeHeartProduct extends BaseModel
{
    public $table = "like_heart_product";

    public $primaryKey = "idLikeHeartProduct";

    protected $fillable = [
        'ip_address',
        'product',
    ];
}
