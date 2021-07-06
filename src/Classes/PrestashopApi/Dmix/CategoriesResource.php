<?php

declare(strict_types=1);

namespace Wsi\PrestashopApi\Dmix;

use Cake\Cache\Cache;
use Wsi\PrestashopApi\Dmix\Resource;
use Wsi\PrestashopApi\Dmix\Api;

class CategoriesResource extends Resource
{
    public $resourceName = 'categories';

    public function __construct()
    {
        parent::__construct($this->resourceName);
    }

    public function getBranchedCategories()
    {
        if (($categories = Cache::read('branchedCategories', 'apiResultsLong')) == false) {
            $categories = $this->getAll();
            $categories = $categories->nest('id', 'parent_category_id');

            $categories = $categories->toArray()[0]->children[0]->children;

            Cache::write('branchedCategories', $categories, 'apiResultsLong');
        }
        return $categories;
    }

    public function getAll(array $modifiers = [])
    {
        return parent::getAll($modifiers)->filter(function ($e) {
            return !in_array($e['name'], ['Distribución', 'Flete', 'Flejes', 'Materiales de construccion', 'Flejes color', 'Flejes laminados en frio', 'Flejes laminados en caliente', 'Scrap', 'Varios']);
        });
    }
}
