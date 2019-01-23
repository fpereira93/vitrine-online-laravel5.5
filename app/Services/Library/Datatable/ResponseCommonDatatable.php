<?php

namespace App\Services\Library\Datatable;

class ResponseCommonDatatable
{

    public $draw = 1;
    public $recordsTotal;
    public $recordsFiltered;
    public $data = [];

    /**
     * [separeteNameColumn retried real name column]
     * @param  String $name
     * @return String
     */
    private function separeteNameColumn(String $name)
    {
        $explode = explode('.', $name);
        return end($explode);
    }

    /**
     * [addAllData Adiciona no formato correta do dataTable]
     */
    private function addAllData(CommonDatatable $dataTableInfor, $responseQuery, $format)
    {
        $referenceColumns = $dataTableInfor->getReferenceColumns();

        foreach ($responseQuery as $dataQuery) {
            $add = [];

            foreach ($referenceColumns as $key => $column) {
                $add[$key] = $dataQuery->{$this->separeteNameColumn($column)};
            }

            if (is_callable($format)){ // custmize format response
                $add = $format($dataQuery, $add);
            }

            array_push($this->data, $add);
        }
    }

    /**
     * [setRecordsFiltered Coloca o valor corretamente caso haja filtro]
     */
    private function setRecordsFiltered(CommonDatatable $dataTableInfor)
    {
        if (count($dataTableInfor->getOnlyFiltersValid()) == 0){
            $this->recordsFiltered = $this->recordsTotal;
        } else {
            $this->recordsFiltered = count($this->data);
        }
    }

    /**
     * [fillDefaultDataTable "Metodo que encapsula funções atribuir dados ao response"]
     */
    public function fillDefaultDataTable(CommonDatatable $dataTableInfor, $query, $format = null)
    {
        $this->draw = ($dataTableInfor->draw + 1);
        $this->recordsTotal = $query->count();

        QueryModelDatatable::fillOrder($dataTableInfor, $query);
        QueryModelDatatable::fillWhere($dataTableInfor, $query);
        QueryModelDatatable::paginate($dataTableInfor, $query);

        $this->addAllData($dataTableInfor, $query->get(), $format);
        $this->setRecordsFiltered($dataTableInfor);
    }
}
