<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResidueTypeDocument extends BaseModel
{
    public $table = "ResidueTypeDocuments";
    public $primaryKey = "ResidueTypeDocumentsId";

    protected $fillable = [
        'ResidueTypeDocumentsId',
        'Description',
        'Link',
        'ResidueTypeId',
    ];

    public function residue() 
    {
        return $this->belongsTo('App\Models\ResidueType', 'ResidueTypeId');
    }
}
