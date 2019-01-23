<?php

namespace App\Services\Library\Datatable;

use Exception;

class CommonDatatable
{

    public $draw;
    private $columns = [];
    private $orders = [];
    private $filters = [];
    private $arrayReferenceColumns = [];
    private $search = null;
    public $start;
    public $length;

    public function __construct($arrayFilter, $arrayReferenceColumns)
    {
        if (empty($arrayFilter)){
            throw new Exception('Filter data not found');
        }

        if (empty($arrayReferenceColumns)){
            throw new Exception('Array Reference Columns not found');
        }

        $this->arrayReferenceColumns = $arrayReferenceColumns;
        $object = $this->forceDecodeArrayFilter($arrayFilter);
        $this->fillCommonDatatable($object);
        $this->fillFilter($object);
    }

    public function getColumns()
    {
        return $this->columns;
    }

    public function getOrders()
    {
        return $this->orders;
    }

    public function getValuesFilter()
    {
        return $this->filters;
    }

    public function setSearch(SearchDatatable $searchDatatable)
    {
        $this->search = $searchDatatable;
    }

    public function getSearch()
    {
        return $this->search;
    }

    /**
     * [forceDecodeArrayFilter 'return object array filter']
     * @param  [array] $arrayFilter
     * @return [object]
     */
    private function forceDecodeArrayFilter($arrayFilter)
    {
        return json_decode(json_encode($arrayFilter), false);
    }

    /**
     * [failDataFilter "testa se filtro valido"]
     */
    private function failDataFilter($filter)
    {
        return (
            is_null($filter->value) ||
            (($filter->type == ValueFilterDatatable::BETWEEN) && (is_null($filter->value[0]) && is_null($filter->value[1])))
        );
    }

    /**
     * [getOnlyFiltersValid "Devolve apenas os filtros validos"]
     */
    public function getOnlyFiltersValid()
    {
        $result = [];
        foreach ($this->getValuesFilter()  as $filter) {
            if (!$this->failDataFilter($filter)){
                array_push($result, $filter);
            }
        }
        return $result;
    }

    /**
     * [getReferenceColumns 'return array of name columns']
     * @return [array]
     */
    public function getReferenceColumns()
    {
        return $this->arrayReferenceColumns;
    }

    /**
     * [fillCommonDatatable Preenche dados padrao]
     */
    private function fillCommonDatatable($object)
    {
        $this->draw = $object->draw;
        $this->start = $object->start;
        $this->length = $object->length;
        $this->setSearch($this->fillSearchDatatable($object));
        $this->fillOrdersDatatable($object);
        $this->fillColumnsDatatable($object);
    }

    /**
     * [fillFilter Preenche os dados para filtrar]
     */
    private function fillFilter($object)
    {
        if (empty($object->filters)){
            return;
        }

        foreach ($object->filters as $filter) {
            $objectAdd = new ValueFilterDatatable();

            $objectAdd->column = $filter->column;
            $objectAdd->type = $filter->type;

            if ($objectAdd->type != ValueFilterDatatable::BETWEEN){
                $objectAdd->value = $filter->value;
            } else {
                $objectAdd->value[0] = $filter->value[0];
                $objectAdd->value[1] = $filter->value[1];
            }

            array_push($this->filters, $objectAdd);
        }
    }

    /**
     * [fillSearchDatatable Cria dados do campo 'filtar']
     */
    private function fillSearchDatatable($object)
    {
        $result = new SearchDatatable();

        $result->value = $object->search->value;
        $result->regex = $object->search->regex;
        return $result;
    }

    /**
     * [fillOrdersDatatable Cria as ordenações]
     */
    private function fillOrdersDatatable($object)
    {
        foreach ($object->order as $_order) {
            $objectAdd = new OrderDatatable();

            $objectAdd->column = $_order->column;
            $objectAdd->dir = $_order->dir;
            array_push($this->orders, $objectAdd);
        }
    }

    /**
     * [fillColumnsDatatable Cria as colunas]
     */
    private function fillColumnsDatatable($object)
    {
        foreach ($object->columns as $column) {
            $objectAdd = new ColumnDatatable();

            $objectAdd->data = $column->data;
            $objectAdd->name = $column->name;
            $objectAdd->searchable = $column->searchable;
            $objectAdd->orderable = $column->orderable;
            array_push($this->columns, $objectAdd);
        }
    }
}
