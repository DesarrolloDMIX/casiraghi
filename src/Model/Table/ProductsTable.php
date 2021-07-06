<?php

declare(strict_types=1);

namespace App\Model\Table;

use Cake\Database\Schema\TableSchemaInterface;
use Cake\ORM\Query;
use Cake\ORM\Table;

class ProductsTable extends Table
{

    protected function _initializeSchema(TableSchemaInterface $schema): TableSchemaInterface
    {
        $schema->setColumnType('metadata', 'json');

        return $schema;
    }

    public function initialize(array $config): void
    {
        $this->hasMany('ProductImages')
            ->setProperty('images')
            ->setSaveStrategy('replace')
            ->setDependent(true);

        $this->belongsTo('DefaultImages')
            ->setForeignKey('default_image_id')
            ->setClassName('ProductImages');

        $this->belongsToMany('Categories');

        $this->belongsTo('DefaultCategories')
            ->setClassName('Categories')
            ->setForeignKey('category_id');
    }

    public function findByCategory(Query $q, array $opt = [])
    {
        $id = $opt['category_id'];
        return $q
            ->matching('Categories', function (Query $q) use ($id) {
                return $q->where(['Categories.id' => $id]);
            })
            ->contain(['DefaultCategories', 'DefaultImages']);
    }

    public function getRandomProducts()
    {
        $products = $this->find()->where(['stock >' => 0])->contain(['ProductImages', 'DefaultImages'])->cache('rand-products', 'dbResults')->toArray();
        $rand = array_rand($products, 12);
        $products = array_intersect_key($products, array_flip($rand));

        return $products;
    }
}
