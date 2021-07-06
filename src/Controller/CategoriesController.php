<?php

namespace App\Controller;

use App\Model\Table\CategoriesTable;
use App\Model\Table\ProductsTable;
use Cake\Cache\Cache;
use Wsi\PrestashopApi\Dmix\CategoriesResource;

class CategoriesController extends AppController
{

    public function displayProductsOfCategory($categoryId)
    {
        // if ($rId !== null) {
        //     $level = 3;
        // } else if ($srId !== null) {
        //     $level = 2;
        // } else {
        //     $level = 1;
        // }

        // $whereinListCondition = [$gsrId, $srId, $rId];

        // $categoryId = $rId !== null ? $rId : ($srId !== null ? $srId : $gsrId);

        // $categoriesTable = new CategoriesTable();

        // $categories = $categoriesTable
        //     ->find()
        //     ->contain(['ChildCategories', 'ParentCategories.ChildCategories'])
        //     ->whereInList('Categories.id', $whereinListCondition)
        //     ->all();

        // $categories = $categories->indexBy('id')->toArray();

        // $gsrCategory = $categories[$gsrId];

        // $category = $categories[$categoryId];

        // $productsTable = new ProductsTable();

        // $products = $productsTable
        //     ->find('byCategory', ['category_id' => $categoryId])
        //     ->where(['stock >' => 0])
        //     ->cache(function (Query $q) use ($categoryId) {
        //         return 'products-' . md5(serialize($categoryId));
        //     }, 'dbResults')
        //     ->toArray();

        if (($categories = Cache::read('categories_with-products-children', 'apiResultsLong')) == false) {
            $categories = (new CategoriesResource())->getAll(['products', 'children_categories'])->indexBy('id')->toArray();

            Cache::write('categories_with-products-children', $categories, 'apiResultsLong');
        }

        $category = $categories[$categoryId];

        $category->children_categories = (new CategoriesTable())->newEntities($category->children_categories);

        $products = (new ProductsTable())->newEntities($category->products);

        foreach ($products as $key => $product) {
            $products[$key]->price = $product->price * 1.21;
        }

        $depthLevel = $category->depth_level - 2;

        $gsrCategory = $category;
        if ($depthLevel > 0) {
            $depthLevelCounter = $depthLevel;
            while ($depthLevelCounter > 0) {
                $gsrCategory = $categories[$gsrCategory->parent_category_id];
                $depthLevelCounter = $depthLevelCounter - 1;
            }
        }

        $this->set(compact('products', "category", 'gsrCategory', 'depthLevel'));

        return $this->render('category');
    }
}
