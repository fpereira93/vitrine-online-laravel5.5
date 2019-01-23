<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProviderDocument extends BaseModel
{
    public $table = "ProviderDocuments";
    public $primaryKey = "ProviderDocumentId";
    
    public function provider()
    {
        return $this->belongsTo('App\Models\Provider', 'ProviderId');
    }
}
