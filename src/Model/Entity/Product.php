<?php

declare(strict_types=1);

namespace App\Model\Entity;

use Cake\Core\Configure;
use Cake\ORM\Entity;

class Product extends Entity
{
    public function _getPriceFraction()
    {
        return (string) number_format((int) $this->price, 0, ',', '.');
    }

    public function _getPriceCents()
    {
        $cents = $this->price - (int) str_replace('.', '', $this->price_fraction);

        $cents = (int) round($cents * 100);

        $cents = (string) ($cents === 100 ? 99 : $cents);

        $cents = strlen($cents) === 1 ? '0' . $cents : $cents;

        return $cents;
    }

    public function formattedPrice()
    {
        return number_format($this->price, 2, ',', '.');
    }

    public function resolveDefaultImageUrl()
    {
        return self::resolveImageUrl($this->default_image_id ?? 0);
    }

    public function resolveImagesUrls()
    {
        $imagesIds = $this->images_list;

        $images = [];
        foreach ($imagesIds as $imageId) {
            $images[] = self::resolveImageUrl((int) $imageId ?? 0);
        }

        return $images;
    }

    static function resolveImageUrl(int $imageId)
    {
        return Configure::read('Prestashop.url') . '/' . $imageId . '/0.jpg';
    }
}
