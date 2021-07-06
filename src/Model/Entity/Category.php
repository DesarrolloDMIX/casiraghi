<?php

declare(strict_types=1);

namespace App\Model\Entity;

use Cake\Core\Configure;
use Cake\ORM\Entity;

class Category extends Entity
{
    static function resolveImageUrl(int $categoryId)
    {
        return Configure::read('Prestashop.url') . '/img/c/' . $categoryId . '.jpg';
    }

    public function resolveDefaultImageUrl()
    {
        return self::resolveImageUrl($this->id);
    }
}
