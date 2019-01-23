<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Services\Traits\FileManipulation;
use App\Services\Library\Common;

class Product extends BaseModel
{
    use FileManipulation;

    public $table = "product";

    public $primaryKey = "idProduct";

    protected $files = [
        'folderName' => 'product'
    ];

    protected $fillable = [
        'name',
        'description',
        'stock',
        'category',
        'brand',
        'price',
        'mainImage',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['liked'];

    public function category()
    {
        return $this->hasOne(Category::class, 'idCategory', 'category');
    }

    public function brand()
    {
        return $this->hasOne(Brand::class, 'idBrand', 'brand');
    }

    public function containers()
    {
        return $this->belongsToMany(Container::class, 'container_product', 'product', 'container');
    }

    public function like()
    {
        return $this->hasMany(LikeHeartProduct::class, 'product', 'idProduct');
    }

    /**
     * Get if Is Like
     *
     * @return bool
     */
    public function getLikedAttribute()
    {
        return LikeHeartProduct::where('product', $this->idProduct)
            ->where('ip_address', Common::getClientIp())
            ->first() ? true : false;
    }
}
