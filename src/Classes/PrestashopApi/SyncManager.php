<?php

namespace Wsi\PrestashopApi;

use App\Model\Table\CategoriesTable;
use Cake\Core\Configure;
use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Cake\Utility\Inflector;
use Wsi\PrestashopApi\PrestashopResource;

class SyncManager
{

    protected $resourcesToTables = [
        'products' => 'Products',
        'categories' => 'Categories',
    ];

    protected $localResourcesTables = [];

    protected $extFieldsToLocalFields = [
        'id' => 'id',
        'id_category_default' => 'category_id',
        'id_default_image' => 'default_image_id',
        'price' => 'price',
        'name' => 'name',
        'description' => 'description',
        'description_short' => 'description_short',
        'id_parent' => 'parent_id',
        'is_root_category' => 'is_root_category',
        'reference' => 'reference',
        'stock' => 'stock',
        'image_url' => 'image_url',
        'position' => 'position'
    ];

    protected $associations = [
        'products' => ['images', 'categories'],
        'categories' => [],
    ];

    public function syncResources(array $resources)
    {
        if (count($resources) == count(array_intersect_key($resources, $this->resourcesToTables))) {

            $ext = $this->getExtResources($resources);

            $listOfExtIds = [];
            foreach ($ext as $resourceName => $resource) {

                $listOfExtIds[$resourceName] = [];
                foreach ($resource as $element) {
                    $listOfExtIds[$resourceName][] = (int) $element['id'];
                }
            }

            $local = $this->getLocalResourcesByExtIds($listOfExtIds);

            $patchedEntities = $this->patchEntities($ext, $local);


            $newExt = [];
            foreach ($patchedEntities as $resourceName => $patchedEntity) {
                $localIds = Hash::extract($patchedEntity, '{*}.id');

                $newExt[$resourceName] = array_filter($ext[$resourceName], function ($element) use ($localIds) {
                    return !in_array($element['id'], $localIds);
                });
            }

            $newEntities = $this->newEntities($newExt);

            $entities = [];
            foreach (array_keys($resources) as $resourceName) {
                $entities[$resourceName] = $patchedEntities[$resourceName] + $newEntities[$resourceName];
            }

            if ($this->saveEntities($entities)) {
                dump('saved');
            } else {
                dd('not saved');
            }

            $entities = $this->saveAssociations($entities, $ext);

            return true;
        } else {

            return 'recurso invalido';
        }
    }

    public function getLocalResources(array $resources)
    {
        $results = [];
        foreach ($resources as $resource) {

            $table = TableRegistry::getTableLocator()->get($this->resourcesToTables[$resource]);

            $results[$resource] = $table->find()->all();
            $this->localResourcesTables[$resource] = $table;
        }

        return $results;
    }

    public function getLocalResourcesByExtIds(array $listOfExtIds)
    {
        $local = [];
        foreach ($listOfExtIds as $resourceName => $extIds) {

            $table = TableRegistry::getTableLocator()->get($this->resourcesToTables[$resourceName]);

            $local[$resourceName] =
                $table
                ->find()
                ->whereInList('id', $extIds)
                ->all();
            $this->localResourcesTables[$resourceName] = $table;
        }

        return $local;
    }

    public function getExtResources(array $resources)
    {
        $extResources = [];
        foreach ($resources as $resourceName => $quantity) {
            $prestashopResource = new PrestashopResource($resourceName);

            if ($quantity === 'all') {
                $extResources[$resourceName] = $prestashopResource->getAll();
            } else if (is_integer((int) $quantity) && $quantity > 0) {
                $extResources[$resourceName] = $prestashopResource->getFirst((int) $quantity);
            } else {
                dd('Not a valid value for resource quantity. Only "all" or an integer greater than 0.');
            }
        }

        return $extResources;
    }

    public function patchEntities(array $ext, array $local)
    {
        $patched = [];
        foreach ($local as $resourceName => $resource) {

            $patched[$resourceName] = [];
            foreach ($resource as $entity) {

                foreach ($ext[$resourceName] as $extElement) {
                    if ($extElement['id'] == $entity->id) {

                        $patched[$resourceName][$extElement['id']] = $this->patchEntity($entity, $extElement, $resourceName);
                    }
                }
            }
        }
        return $patched;
    }

    public function patchEntity(Entity $entity, array $extElement, $resourceName)
    {

        $table = $this->localResourcesTables[$resourceName];

        $fields = array_intersect_key($extElement, $this->extFieldsToLocalFields);

        foreach ($fields as $key => $value) {
            $newKey = $this->extFieldsToLocalFields[$key];
            unset($fields[$key]);
            $fields[$newKey] = $value;
        }


        $patched = $table->patchEntity($entity, $fields);

        return $patched;
    }

    public function newEntities(array $newExt)
    {
        $new = [];
        foreach ($newExt as $resourceName => $resource) {
            $new[$resourceName] = [];
            foreach ($resource as $newElement) {
                $new[$resourceName][$newElement['id']] = $this->newEntity($newElement, $resourceName);
            }
        }

        return $new;
    }

    public function newEntity(array $newElement, string $resourceName)
    {
        $table = $this->localResourcesTables[$resourceName];

        $fields = array_intersect_key($newElement, $this->extFieldsToLocalFields);


        foreach ($fields as $key => $value) {
            $newKey = $this->extFieldsToLocalFields[$key];
            unset($fields[$key]);
            $fields[$newKey] = $value;
        }

        $new = $table->newEntity($fields);

        return $new;
    }

    public function saveEntities(array $listOfEntities)
    {
        foreach ($listOfEntities as $resourceName => $resource) {
            $table = $this->localResourcesTables[$resourceName];

            if (!$table->saveMany($resource)) {
                return false;
            }
        }
        return true;
    }

    public function saveAssociations(array $entities, array $ext)
    {
        foreach ($entities as $resourceName => $resource) {
            if ($this->associations[$resourceName] !== []) {

                $fnName = 'SaveAssociations' . Inflector::camelize($resourceName);

                $this->{$fnName}($resource, $ext[$resourceName]);
            }
        }
    }

    public function saveAssociationsProducts(array $entities, array $extResource)
    {
        $productsTable = $this->localResourcesTables['products'];

        $categoriesTable = new CategoriesTable();

        $patched = [];

        foreach ($entities as $key => $entity) {

            if ($extResource[$key]['associations']['images']) {
                $assocImages = $extResource[$key]['associations']['images'];
            } else {
                $assocImages = [
                    [
                        'url' => '/prestashop-images/no-image.png',
                        'id' => ($entity->id * 2) - 1,
                    ],
                    [
                        'url' => '/prestashop-images/no-image.png',
                        'id' => $entity->id * 2,
                    ],
                ];

                $entity->default_image_id = $assocImages[0]['id'];
            }

            foreach ($assocImages as $keyImage => $assocImage) {
                $assocImage['product_id'] = $entity->id;
                $assocImage['url'] = isset($assocImage['url']) ?
                    $assocImage['url'] :
                    '/prestashop-images/products/' .
                    $entity->id .
                    '-' .
                    $assocImage['id'] .
                    '.jpg';

                $assocImages[$keyImage] = $assocImage;
            }

            $assocCategories = $extResource[$key]['associations']['categories'];

            $categoriesIds = Hash::extract($assocCategories, '{*}.id');

            $categoriesEntities = $categoriesTable->find()->whereInList('id', $categoriesIds)->toArray();

            $productsTable->Categories->link($entity, $categoriesEntities);
            $entity = $productsTable->patchEntity($entity, ['images' => $assocImages]);

            $patched[] = $entity;
        }
        if (!$productsTable->saveMany($patched)) {
            return false;
        }
        return true;
    }
}
