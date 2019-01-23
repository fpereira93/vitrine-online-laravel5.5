<?php

namespace App\Services\Library\Datatable;

class ValueFilterDatatable
{

    const LIKE = 'like';
    const EQUAL = 'equal';
    const BETWEEN = 'between';

    public $column;
    public $value = [];
}
