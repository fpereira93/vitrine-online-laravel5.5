<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Services\Traits\FileManipulation;

class Provider extends BaseModel
{
    use FileManipulation;

    public $table = "Providers";

    public $primaryKey = "ProviderId";

    protected $files = [
        'folderName' => 'provider'
    ];

    public $fillable = [
        'SocialName',
        'FantasyName',
        'CNPJ',
        'IE',
        'PhoneNumber',
        'PhoneNumber2',
        'Mail',
        'WebSite',
        'Street',
        'Number',
        'District',
        'City',
        'State'
    ];

    public function residues()
    {
        return $this->belongsToMany('App\Models\ResidueType', 'ProviderAssistedResidues', 'ProviderId','ResidueTypeId');
    }
}
