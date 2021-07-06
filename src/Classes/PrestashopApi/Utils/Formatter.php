<?php

declare(strict_types=1);

namespace Wsi\PrestashopApi\Utils;

use SimpleXMLElement;

class Formatter
{
    static function string(string $str)
    {
        // $str = preg_replace('/\s+/', ' ', $str);

        $str = trim($str);

        return $str;
    }

    static function toArray($xml)
    {

        $array = (array) $xml;

        foreach ($array as $key => $value) {

            if (is_object($value)) {
                if (count($value) > 0 && count($value->attributes()) > 0) {
                    $value = self::extractAssocArray($value);
                }
            }

            if (is_array($value)) {
                $value = self::toArray($value);
            }
            if (is_object($value)) {
                if (count($value) > 0) {
                    $value = self::toArray($value);
                } else {
                    $value = null;
                }
            }

            if (is_string($value)) {
                $value = self::string($value);
            }

            $array[$key] = $value;
        }

        return $array;
    }

    static function extractAssocArray(SimpleXMLElement $xml)
    {
        $array = [];

        foreach ($xml->children() as $key => $value) {
            $array[] = self::toArray($value);
        }

        foreach ($xml as $key => $value) {
            if ($value->children()->getName() == "id") {
                $arrayIds[] = (int) $value->children();
            }
        }

        return $array;
    }
}
