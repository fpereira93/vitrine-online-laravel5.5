<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Services\Traits\FileManipulation;

class ContainerProduct extends BaseModel
{
    use FileManipulation;

    public $table = "container_product";

    public $primaryKey = "idContainerProduct";

    protected $files = [
        'folderName' => 'container_product'
    ];

    protected $fillable = [
        'container',
        'product',
    ];

    public function container()
    {
        return $this->hasOne(Container::class, 'idContainer', 'container');
    }

    public function product()
    {
        return $this->hasOne(Product::class, 'idProduct', 'product');
    }
}
