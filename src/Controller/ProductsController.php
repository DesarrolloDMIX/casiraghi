<?php

namespace App\Controller;

use App\Model\Table\ProductsTable;
use Wsi\PrestashopApi\Dmix\ProductsResource;

class ProductsController extends AppController
{

    public function displayProduct($productId)
    {
        $product = (new ProductsResource())->getAll(['images_list']);

        $product = $product->firstMatch(['id' => $productId]);

        $this->set(compact('product'));

        return $this->render('product');
    }
}
