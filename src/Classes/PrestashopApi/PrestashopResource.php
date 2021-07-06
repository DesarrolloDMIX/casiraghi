<?php

namespace Wsi\PrestashopApi;

use Cake\Core\Configure;
use PrestaShopWebservice;
use SimpleXMLElement;
use Wsi\PrestashopApi\Utils\Formatter;

class PrestashopResource
{
    protected $pws_key;

    protected $pws;

    protected $wsi_key;

    protected $resource;

    protected $opt;

    protected $_images = [];

    protected $filters = [];

    public $url;

    protected $specialRules = ['categories', 'products'];

    public function __construct(String $resource)
    {
        $this->url = Configure::read('Prestashop.url');
        $this->pws_key = Configure::read('Prestashop.pws_key');
        $this->pws = new PrestaShopWebservice($this->url . '/', $this->pws_key, false);
        $this->wsi_key = Configure::read('Prestashop.wsi_key');

        $this->resource = $resource;

        $this->opt = [
            'resource' => $this->resource
        ];
    }

    /**
     * Returns a single instance of the specified resource in an array format.
     *
     * @param Int $id The id of the element to find.
     * 
     * @return Array
     **/
    public function getById(Int $id)
    {
        $opt = $this->opt;
        $opt['id'] = $id;

        $xml = $this->pws->get($opt)->children()->children();

        $array = $this->toArray($xml);
        $array = $this->extractLanguage($array);

        $specialRuleKey = array_search($this->resource, $this->specialRules);
        if ($specialRuleKey !== false) {
            $array = $this->{$this->specialRules[$specialRuleKey]}($array);
        }

        return $array;
    }

    /**
     * Applies a filter to the PrestashopResource instance that will be used when calling the getAllIds(), getAll() or getFirst() method
     *
     * The possible values for the $type parameter are:
     * - Literal
     * - Or (not implemented, yet)
     * - Interval (not implemented, yet)
     * - Begin (not implemented, yet)
     * - End (not implemented, yet)
     * - Contains (not implemented, yet)
     *
     * @param string $field
     * @param string|int|array $value if value is an array, the only possible value for $type is "Or"
     * @param string $type
     * @return type
     **/
    public function applyFilter(string $field, $value, string $type = 'Literal')
    {
        $this->filters[$field] = $value;
    }

    /**
     * Returns an array containing all the elements of the specified resource.
     * 
     * @return Array
     **/
    public function getAll()
    {
        $arrayIds = $this->getAllIds();

        $elements = [];

        foreach ($arrayIds as $id) {
            $elements[$id] = $this->getById($id);
        }

        return $elements;
    }

    /**
     * Returns an array containing the id's of all the elements of the specified resource.
     *
     * @return Array
     **/
    public function getAllIds()
    {
        $opt = $this->opt;
        foreach ($this->filters as $field => $value) {
            $opt['filter[' . $field . ']'] = $value;
        }

        $xml = $this->pws->get($opt)->children()->children();

        $arrayIds = [];

        foreach ($xml as $value) {
            $arrayIds[] = (int) $value->attributes()->id;
        }

        return $arrayIds;
    }

    public function getFirst(int $quantity = 1)
    {
        $arrayIds = $this->getAllIds();

        $elements = [];

        for ($i = 0; $i < $quantity; $i++) {
            $elements[$arrayIds[$i]] = $this->getById($arrayIds[$i]);
        }

        return $elements;
    }

    /**
     * Transforms a SimpleXmlElement into an array, including nested elements
     *
     * @param SimpleXmlElement|Array $xml A SimpleXmlElement or an array or other iterables that can be casted into arrays
     * @return Array
     **/
    protected function toArray($xml)
    {

        $array = (array) $xml;

        foreach ($array as $key => $value) {

            if ($key == 'id_parent' && !is_array($value)) {
                $value = (string) $value;
            }
            if ($key == 'id_category_default' && !is_array($value)) {
                $value = (string) $value;
            }
            if ($key == 'id_default_image' && !is_array($value)) {
                $value = ((string) $value) !== '' ? (string) $value : null;
            }
            if ($key == 'id_tax_rules_group' && !is_array($value)) {
                $value = ((string) $value) !== '' ? (string) $value : null;
            }

            if (is_object($value)) {
                if (count($value) > 0 && count($value->attributes()) > 0) {
                    $value = $this->extractAssocArray($value);
                }
            }

            if (is_array($value)) {
                $value = $this->toArray($value);
            }
            if (is_object($value)) {
                if (count($value) > 0) {
                    $value = $this->toArray($value);
                } else if ($key == 'language') {
                    $ar = (array) $value;
                    if (isset($ar[0])) {
                        $value = ((array) $value)[0];
                    } else {
                        $value = null;
                    }
                } else {
                    $value = null;
                }
            }

            if (is_string($value)) {
                $value = Formatter::string($value);
            }

            $array[$key] = $value;
        }

        return $array;
    }

    protected function extractLanguage(array $element)
    {
        foreach ($element as $key => $value) {
            if (is_array($value) && array_key_exists('language', $value)) {

                $languageValue = $value['language'];

                $newKey = $key . '_lang';

                $element[$newKey] = $languageValue;
                unset($element[$key]);
                if (is_array($languageValue)) {
                    $element[$key] = $languageValue[0];
                } else {
                    $element[$key] = $languageValue;
                }
            }
        }
        return $element;
    }

    protected function extractAssocArray(SimpleXMLElement $xml)
    {
        $array = [];

        foreach ($xml->children() as $key => $value) {
            $array[] = $this->toArray($value);
        }

        foreach ($xml as $key => $value) {
            if ($value->children()->getName() == "id") {
                $arrayIds[] = (int) $value->children();
            }
        }

        return $array;
    }

    protected function getBlankSchema()
    {
        $xml = $this->pws->get([
            'url' => $this->url . '/api/' . $this->resource . '?schema=blank'
        ]);

        return $xml;
    }

    public function addCart(array $element)
    {
        $blank = $this->getBlankSchema();

        $xmlFields = $blank->children()->children();

        if (count($element['associations']['cart_rows']) == 1) {
            $xmlFields->id_currency = 1;
            $xmlFields->id_lang = 1;
            $xmlFields->associations->cart_rows->cart_row->id_product = $element['associations']['cart_rows'][0]['id_product'];
            $xmlFields->associations->cart_rows->cart_row->quantity = $element['associations']['cart_rows'][0]['quantity'];
            $xmlFields->associations->cart_rows->cart_row->id_product_attribute = $element['associations']['cart_rows'][0]['id_product_attribute'];
        } else {
            foreach ($xmlFields as $key => $value) {
                if (array_key_exists($key, $element) && $key != 'associations') {
                    $xmlFields->{$key} = $element[$key];
                }
            }

            $xmlAssoc = $blank->children()->children()->associations;

            foreach ($xmlAssoc->children() as $key => $value) {
                if (array_key_exists($key, $element['associations'])) {

                    $assocSingularName = $value->children()->getName();

                    unset($xmlAssoc->{$key}->{$assocSingularName});

                    foreach ($element['associations'][$key] as $index => $value) {
                        $newField = $xmlAssoc->{$key}->addChild($assocSingularName);

                        foreach ($value as $key2 => $value2) {
                            $newField->addChild($key2, $value2);
                        }
                    }
                }
            }
        }

        $opt = $this->opt;
        $opt['postXml'] = $blank->asXML();

        $newCart = $this->pws->add($opt)->children()->children();

        $newCart = $this->toArray($newCart);

        return $newCart;
    }

    protected function categories(array $category)
    {
        if ($this->_images === []) {
            $psImages = new PrestashopImageResource('categories');
            $this->_images = $psImages->getAll();
        }

        foreach ($this->_images as $image) {
            if ($image['id_link'] == $category['id']) {
                $category['image_url'] = '/prestashop-images/categories/' . $category['id'] . '.jpg';
                break;
            }
        }

        if (!isset($category['image_url'])) {
            $category['image_url'] = '/prestashop-images/no-image.png';
        }

        return $category;
    }

    public function products(array $product)
    {
        $tax = $product['id_tax_rules_group'];
        switch ($tax) {
            case 1:
                $product['price'] = $product['price'] * 1.21;
                break;
            case 2:
                $product['price'] = $product['price'] * 1.105;
                break;
            default:
                $product['price'] = $product['price'];
                break;
        }

        $stockAvailablesId = $product['associations']['stock_availables'][0]['id'];

        $stockAvailables = new PrestashopResource('stock_availables');

        $stock = $stockAvailables->getById($stockAvailablesId)['quantity'];

        $product['stock'] = $stock;

        return $product;
    }
}
