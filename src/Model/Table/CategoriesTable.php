<?php

declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\Table;

class CategoriesTable extends Table
{
    public function initialize(array $config): void
    {
        // $this->belongsToMany('Products');

        $this->belongsTo('ParentCategories')
            ->setClassName('Categories')
            ->setForeignKey('parent_id');

        $this->hasMany('ChildCategories')
            ->setClassName('Categories')
            ->setForeignKey('parent_id');
    }

    public function findGSR(Query $q, array $options)
    {
        $matched = $q->matching('ParentCategories', function (Query $q) {
            return $q->where(['ParentCategories.is_root_category' => 1]);
        });

        return $matched->order(['Categories.position' => 'ASC']);
    }

    public function getBranchedCategories()
    {
        $branchedCategories = $this->find()
            ->contain(['ChildCategories'])
            ->cache('categories', 'dbResults')
            ->nest('id', 'parent_id')
            ->toArray();

        return $branchedCategories[0]->children[0]->children;
    }
}
