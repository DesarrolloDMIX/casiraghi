<?php

declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;

class ProductImagesTable extends Table
{
    public function initialize(array $config): void
    {
        $this->belongsTo('Products');
    }
}
