<?php

namespace App\Controller;

use Cake\Cache\Cache;
use Cake\Collection\Collection;
use Cake\Http\Client;
use Wsi\PrestashopApi\Dmix\Api;
use Wsi\PrestashopApi\Dmix\CategoriesResource;
use Wsi\PrestashopApi\Dmix\ProductsResource;

class TestDmixApiController extends AppController
{

    public function index()
    {
        $time_start = microtime(true);

        $client = new Client();

        if (($jsonCategories = Cache::read('categories', 'apiResultsLong')) == NULL) {
            $jsonCategories = $client->get('http://psdev/module/dmixapi/categories', ['modifiers' => serialize(['parent', 'products'])])->getBody()->getContents();
            Cache::write('categories', $jsonCategories, 'apiResultsLong');
        }

        if (($jsonProducts = Cache::read('products', 'apiResultsLong')) == NULL) {
            $jsonProducts = $client->get('http://psdev/module/dmixapi/products')->getBody()->getContents();
            Cache::write('products', $jsonProducts, 'apiResultsLong');
        }

        $categories = new Collection(json_decode($jsonCategories, true));
        $products = new Collection(json_decode($jsonProducts, TRUE));

        $products = $products->indexBy('id_product');

        $categories = $categories->map(function ($category, $key) use ($products) {
            if ($category['id_products']) {
                $productsIds = explode(',', $category['id_products']);
                $categoryProducts = array_intersect_key($products->toArray(), array_flip($productsIds));

                $category['products'] = $categoryProducts;
                return $category;
            }
            return $category;
        });

        $categories = $categories
            ->nest('id_category', 'id_parent');

        dump($categories->toArray());
        dump($products->toArray());

        $time_end = microtime(true);

        dump($time_end - $time_start);

        dump('end');
        die;
    }

    public function bring()
    {
        $r = $this->request->getQuery('r');
        $id = (int)$this->request->getQuery('id');

        $result = (new Api())->bring($r, ['id' => $id]);

        dump($result);
        die;
    }

    public function products()
    {
        dump($products = (new ProductsResource())->getAll(['images']));

        dd('end');
    }

    public function categories()
    {
        dump($categories = (new CategoriesResource())->getBranchedCategories());
        dd('end');
    }
}
