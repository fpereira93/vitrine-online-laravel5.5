<?php

namespace App\Services\Api;

use DB;
use Exception;
use App\Exceptions\Api\DuplicateDataException;
use App\Exceptions\Api\RegisterNotFoundException;
use App\Exceptions\Api\NotAllowedDeletionException;
use App\Models\Product;
use App\Models\Container;
use App\Models\LikeHeartProduct;
use App\Services\BaseService;
use App\Services\Library\Common;
use App\Services\Library\ServiceData;
use App\Services\Library\Datatable\CommonDatatable;
use App\Services\Library\Datatable\ResponseCommonDatatable;

class ProductService extends BaseService
{

    private function forceDeleteFiles(array $imagensId, $product)
    {
        $this->holdMistake(function() use ($imagensId, $product) {
            foreach ($imagensId as $imageId) {
                $product->deleteFile($imageId);
            }
        });
    }

    public function createOrUpdate($params)
    {
        return $this->transaction(function() use ($params){

            $data = $this->validate($params, [
                'idProduct' => 'required|integer',
                'name' => 'required',
                'description' => 'required',
                'price' => 'required|numeric',
                'stock' => 'required|integer',
                'category' => 'required|exists:category,idCategory',
                'brand' => 'required|exists:brand,idBrand',
                'containers' => 'required',
            ], true);

            $mainImage = $params['mainImage'];
            unset($params['mainImage']);

            $product = Product::updateOrCreate(['idProduct' => $params['idProduct']], $params);

            if (isset($params['imagesDeleted'])){
                $this->forceDeleteFiles($params['imagesDeleted'], $product);
            }

            if ($mainImage['fileId']){
                $product->mainImage = $mainImage['fileId'];
                $product->save();
            }

            if (isset($params['images'])){
                foreach ($params['images'] as $image) {

                    $file = $product->saveFile($image);

                    if (!$mainImage['fileId'] && $image['checked'] == 1){
                        $product->mainImage = $file->FileId;
                        $product->save();
                    }
                }
            }

            $product->containers()->sync($params['containers']);

            return $data->setData($product)->setMessage("Produto criado / atualizado com sucesso");
        });
    }

    public function delete(int $id)
    {
        return $this->transaction(function() use ($id){
            $product = Product::find($id);

            $product->mainImage = null;
            $product->save();

            $this->holdMistake(function() use ($product) {
                $product->deleteFile();
            });

            $product->containers()->detach();

            if (!$product->delete()){
                throw new NotAllowedDeletionException('Não foi possível apagar o Produto.');
            }

            return new ServiceData(true);
        });
    }

    private function getQueryForFilter()
    {
        $fields = [
            'product.idProduct',
            'name',
            'description',
            'count_likes_product.likes',
        ];

        $query = Product::select($fields)
            ->leftJoin('count_likes_product', 'count_likes_product.idProduct', '=', 'product.idProduct');

        return $query;
    }

    public function paginate($filterArray)
    {
        return $this->holdMistake(function() use ($filterArray){

            $query = $this->getQueryForFilter();

            $dataTableFilter = new CommonDatatable($filterArray, [
                'idProduct' => 'idProduct',
                'name' => 'name',
                'description' => 'description',
                'likes' => 'count_likes_product.likes',
            ]);

            $response = new ResponseCommonDatatable();
            $response->fillDefaultDataTable($dataTableFilter, $query);

            return new ServiceData($response);
        });
    }

    public function detail(int $id)
    {
        return $this->holdMistake(function() use ($id){

            $product = Product::with(['category', 'brand', 'containers'])->find($id);

            $files = $product->files();

            $product->images = array_map(function($file) use ($product){
                return [
                    'fileId' => $file['FileId'],
                    'url' => $product->generateUrl($file['FileId']),
                    'checked' => $product->mainImage == $file['FileId'],
                ];
            }, $files);

            return new ServiceData($product);
        });
    }

    public function containers()
    {
        return $this->holdMistake(function(){
            return new ServiceData(Container::all());
        });
    }

    private function converterDataSearch($productsDb)
    {
        $response = $productsDb->map(function($product)
        {
            $product->urlMainImage = $product->generateUrl($product->mainImage);
            $product->append('liked');

            $product->allUrlImage = array_map(function($file) use ($product){
                return $product->generateUrl($file['FileId']);
            }, $product->files());

            return $product;
        });

        return $response;
    }

    private function makeQueryWithFilter(int $container, array $filter)
    {
        $query = Product::query();

        $query->with('category', 'brand');

        if (!empty($filter['categories'])){
            $query->whereHas('category', function($q) use ($filter){
                $q->whereIn('idCategory', $filter['categories']);
            });
        }

        if (!empty($filter['brands'])){
            $query->whereHas('brand', function($q) use ($filter){
                $q->whereIn('idBrand', $filter['brands']);
            });
        }

        if (!empty($filter['price'])){
            $query->whereBetween('price', array($filter['price']['min'], $filter['price']['max']));
        }

        $query->whereHas('containers', function($q) use ($container){
            $q->where('idContainer', $container);
        });

        $query->orderBy('idProduct', 'desc');

        return $query;
    }

    private function paginateQuery($query, int $take, int $page)
    {
        $count = $query->count();

        $query->take($take)->skip($page * $take);

        return (object) [
            'count' => $count,
        ];
    }

    public function searchProducts(array $params)
    {
        return $this->holdMistake(function() use ($params){

            $response = $this->validate($params, [
                'container' => 'required|exists:container,idContainer',
            ], true);

            $queryProducts = $this->makeQueryWithFilter($params['container'], $params);

            if (!empty($params['paginate'])){
                $paginate = $this->paginateQuery($queryProducts, $params['paginate']['take'], $params['paginate']['page']);

                $response->setData([
                    'items' => $this->converterDataSearch($queryProducts->get()),
                    'count' => $paginate->count
                ]);
            } else {
                $response->setData($this->converterDataSearch($queryProducts->get()));
            }

            return $response->setMessage('Produtos recuperados com sucesso');
        });
    }

    public function likeHeartProduct(int $productId, bool $isLike)
    {
        return $this->holdMistake(function() use ($productId, $isLike){

            $ip_address = Common::getClientIp();
            $search = ['ip_address' => $ip_address, 'product' => $productId];

            if ($isLike){
                $response = LikeHeartProduct::updateOrCreate($search, $search);
            } else {
                $response = LikeHeartProduct::where($search)->delete();
            }

            return new ServiceData($response);
        });
    }

}
