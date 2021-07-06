<?php

namespace App\Controller\Admin;

use App\Controller\AppController;
use App\Model\Table\ProductsTable;
use Cake\Event\EventInterface;
use Cake\Http\Client;
use Wsi\PrestashopApi\PrestashopImageResource;
use Wsi\PrestashopApi\PrestashopResource;
use Wsi\PrestashopApi\SyncManager;
use Wsi\PrestashopApi\Utils\Meta;

class PrestaSyncController extends AppController
{
    public function beforeFilter(EventInterface $event)
    {
        $this->viewBuilder()->disableAutoLayout();
    }

    public function index()
    {
        $time_start = microtime(true);

        $resources = $this->request->getQueryParams();

        if (is_array($resources) && $resources !== []) {

            set_time_limit(0);
            $syncManager = new SyncManager();

            $syncResult = $syncManager->syncResources($resources);
            $this->set(compact('syncResult'));
        }

        dump((microtime(true) - $time_start) / 60);
        dd('endEnd');
    }

    public function processProductsMeta()
    {
        $table = new ProductsTable();

        $products = $table->find()->contain(['DefaultCategories'])->toArray();

        foreach ($products as $product) {
            $name = $product->name;
            $pattern = $product->default_category->product_name_pattern;

            $meta = Meta::getMetaFromName($name, $pattern);

            $product->metadata = $meta;
        }

        if (!$table->saveMany($products)) {
            dd('Couldn\'t save entities');
        }

        dd('Metadata saved');
    }

    public function bring(string $resourceName, string $id)
    {
        $resource = new PrestashopResource($resourceName);
        if ($id == 0) {
            dd($resource->getAll());
        }

        dd($resource->getById($id));
    }

    public function syncImages()
    {
        set_time_limit(0);
        $psImagesProducts = new PrestashopImageResource('products');
        $psImagesCategories = new PrestashopImageResource('categories');

        $limit = isset($_GET['limit']) ? $_GET['limit'] : 1000;
        $offset = isset($_GET['offset']) ? $_GET['offset'] : 0;

        $http = new Client();

        if (isset($_GET['c'])) {
            $categoriesImages = $psImagesCategories->getAll($limit, $offset);

            foreach ($categoriesImages as $categoryImage) {
                $image = $http->get($categoryImage['url']);

                $imageContents = $image->getBody()->getContents();

                file_put_contents(WWW_ROOT . 'prestashop-images/categories/' . $categoryImage['id_link'] . '.jpg', $imageContents);
            }
        }

        if (isset($_GET['p'])) {
            $productsImages = $psImagesProducts->getAll($limit, $offset);

            foreach ($productsImages as $productImage) {
                foreach ($productImage['declinations'] as $declination) {
                    $image = $http->get($declination['url']);

                    $imageContents = $image->getBody()->getContents();

                    file_put_contents(
                        WWW_ROOT . 'prestashop-images/products/' .
                            $productImage['id_link'] .
                            '-' .
                            $declination['id'] .
                            '.jpg',
                        $imageContents
                    );
                }
            }
        }

        dd('saved');
    }
}
