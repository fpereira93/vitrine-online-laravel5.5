<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProviderAssistedResidue extends BaseModel
{
    public $table = "ProviderAssistedResidues";

    public $primaryKey = "ProviderAssistedResidueId";

    public function provider()
    {
        return $this->belongsTo('App\Models\Provider', 'ProviderId');
    }
}
