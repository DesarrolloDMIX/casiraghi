<?php

declare(strict_types=1);

namespace Wsi\PrestashopApi\Dmix;

use Cake\Cache\Cache;
use Cake\Collection\Collection;
use Wsi\PrestashopApi\Dmix\Resource;
use Wsi\PrestashopApi\Dmix\Api;

class ProductsResource extends Resource
{
    public $resourceName = 'products';

    public function __construct()
    {
        parent::__construct($this->resourceName);
    }

    public function getRandomProducts()
    {
        $products = $this->getAll()->toArray();

        if ($products) {
            $keys = array_rand($products, 9);
            $products = array_intersect_key($products, array_flip($keys));

            return $products;
        } else {
            return false;
        }
    }

    public function getAll($modifiers = []): Collection
    {
        $cacheKey = 'products';
        sort($modifiers, SORT_STRING);
        foreach ($modifiers as $modifier) {
            $cacheKey .= '-' . $modifier;
        }
        if (($products = Cache::read($cacheKey, 'apiResultsShort')) == false) {
            $products = parent::getAll($modifiers);

            $proccessedProducts = [];
            foreach ($products as $product) {
                $product->price *= 1.21;
                $proccessedProducts[] = $product;
            }

            $products = new Collection($proccessedProducts);

            Cache::write($cacheKey, $products, 'apiResultsShort');
        }
        return $products;
    }
}
