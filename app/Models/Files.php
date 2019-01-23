<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Files extends BaseModel
{
    public $table = "files";
    public $primaryKey = "FileId";

    protected $fillable = [
        'ModuleName',
        'Name',
        'Description',
        'OriginalName',
        'MimeType',
        'Size',
        'IdentifierModule',
    ];
}
