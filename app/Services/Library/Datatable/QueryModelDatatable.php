<?php

namespace App\Services\Library\Datatable;

use Exception;

class QueryModelDatatable
{

    /**
     * [fillOrder Preenche ordenacao no query]
     */
    public static function fillOrder(CommonDatatable $commonDatatable, $query)
    {
        $referenceColumns = $commonDatatable->getReferenceColumns();
        $columns = $commonDatatable->getColumns();

        foreach ($commonDatatable->getOrders() as $order) {
            $column = $columns[$order->column];

            if (!$column->orderable){
                continue; //do not orderable column
            }

            if (!isset($referenceColumns[$column->name])){
                throw new Exception('Column for apply order not found');
            }

            $query->orderBy($referenceColumns[$column->name], $order->dir);
        }
    }

    /**
     * [fillWhere Preenche as condicoes 'where']
     */
    public static function fillWhere(CommonDatatable $commonDatatable, $query)
    {
        $referenceColumns = $commonDatatable->getReferenceColumns();

        foreach ($commonDatatable->getOnlyFiltersValid() as $filter) {

            if (!isset($referenceColumns[$filter->column])){
                throw new Exception('Column for apply conditional not found');
            }

            $column = $referenceColumns[$filter->column];

            if ($filter->type == ValueFilterDatatable::LIKE){
                $query->where($column, 'like', '%' . $filter->value . '%');
            } else if ($filter->type == ValueFilterDatatable::EQUAL){
                $query->where($column, $filter->value);
            } else if ($filter->type == ValueFilterDatatable::BETWEEN){

                if (!is_null($filter->value[0]) && is_null($filter->value[1])){
                    $query->where($column, '>=', $filter->value[0]);
                } else if (is_null($filter->value[0]) && !is_null($filter->value[1])){
                    $query->where($column, '<=', $filter->value[1]);
                } else {
                    $query->whereBetween($column, [$filter->value[0], $filter->value[1]]);
                }

            }
        }
    }

    /**
     * [paginate Realiza a paginacao]
     */
    public static function paginate(CommonDatatable $commonDatatable, $query)
    {
        $query->take($commonDatatable->length)->skip($commonDatatable->start);
    }
}
