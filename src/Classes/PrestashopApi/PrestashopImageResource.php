<?php

namespace Wsi\PrestashopApi;

use Cake\Core\Configure;
use PrestaShopWebservice;
use SimpleXMLElement;
use Wsi\PrestashopApi\Utils\Formatter;

class PrestashopImageResource
{
    protected $pws_key;

    protected $pws;

    protected $wsi_key;

    protected $resource;

    protected $opt;

    public $url;

    public $imagesUrl;

    public function __construct(String $resource)
    {
        $this->url = Configure::read('Prestashop.url');
        $this->pws_key = Configure::read('Prestashop.pws_key');
        $this->pws = new PrestaShopWebservice($this->url, $this->pws_key, false);
        $this->wsi_key = Configure::read('Prestashop.wsi_key');

        $this->resource = 'images/' . $resource;

        $baseUrlImages = str_replace('http://', '', $this->url);

        $this->imagesUrl = 'http://' . Configure::read('Prestashop.pws_key') . '@' . $baseUrlImages . '/api/' . $this->resource . '/';

        $this->opt = [
            'resource' => $this->resource
        ];
    }

    public function getById(int $id)
    {
        $all = $this->getAll();

        foreach ($all as $image) {
            if ($image['id_link'] == $id) {
                return $image;
            }
        }

        return FALSE;
    }

    public function getAll(int $limit = 1000, int $offset = 0): array
    {
        $opt = $this->opt;

        $xml = $this->pws->get($opt);

        $array = (array) $xml->images;

        if (!isset($array['image'])) {
            return [];
        } else {
            $images = $array['image'];
        }

        $images = array_slice($images, $offset, $limit);

        $images = $this->toArray($images);

        return $images;
    }

    protected function toArray($xml)
    {
        $array = json_decode(json_encode($xml), TRUE);

        $arrayResult = [];

        if (count($array) == 1) {
            $array = [$array];
        }

        foreach ($array as $key => $value) {
            $arrayResult[$key] = [
                'id_link' => $value['@attributes']['id'],
                'url' => $this->imagesUrl . $value['@attributes']['id'],
            ];

            if ($this->resource == 'images/products') {
                $arrayResult[$key] = $arrayResult[$key] + ['declinations' => $this->getProductDeclinations($value['@attributes']['id'], $arrayResult[$key]['url'])];
            }
        }

        return $arrayResult;
    }

    protected function getProductDeclinations($id, $url)
    {
        $opt = ['id' => $id];
        $opt = $this->opt + $opt;

        $xml = $this->pws->get($opt);

        $declinations = json_decode(json_encode($xml), TRUE)['image']['declination'];

        $result = [];
        foreach ($declinations as $key => $declination) {
            if ($key === '@attributes') {
                $result[] = [
                    'id' => $declination['id'],
                    'url' => $url . '/' . $declination['id'],
                ];
            } else {
                $result[] = [
                    'id' => $declination['@attributes']['id'],
                    'url' => $url . '/' . $declination['@attributes']['id'],
                ];
            }
        }

        return $result;
    }
}
