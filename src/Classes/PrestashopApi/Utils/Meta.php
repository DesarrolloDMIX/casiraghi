<?php

namespace Wsi\PrestashopApi\Utils;

use Cake\Utility\Text;


class Meta
{
    /**
     * Gets the metadata of a something from its name, using the pattern given in the second parameter.
     *
     * $pattern must follow the next logic:
     *      Name: Example 1 2 3
     *      Pattern: Example {number1} {number2} {number3}
     *   with the example above, the metadata will result in:
     *      ["number1" => 1, "number2" => 1, "number3" => 1]
     *
     * @param string $name The name of the element to extract the metadata from.
     * @param string|null $pattern If pattern is null, the return value will be an empty array.
     * @return array
     **/
    static function getMetaFromName(string $name, $pattern)
    {
        if ($pattern == null || $pattern === '') {
            return [];
        }

        $meta = [];

        $keyStart = strpos($pattern, '{') + 1;

        if ($keyStart === false) {
            return [];
        }

        $keyEnd = strpos($pattern, '}');

        $key = substr($pattern, $keyStart, $keyEnd - $keyStart);

        $pattern = substr($pattern, $keyEnd + 1);

        $thereIsNext = strpos($pattern, '{');

        $after = $thereIsNext !== FALSE ? strstr($pattern, '{', TRUE) : $pattern;

        $name = substr($name, $keyStart - 1);

        if ($after !== '') {
            $value = strstr($name, $after, TRUE);

            $name = strstr($name, $after);
        } else {
            $value = $name;

            $name = $name;
        }

        if ($thereIsNext) {
            $meta = $meta + self::getMetaFromName($name, $pattern);
        }

        $meta[$key] = $value;

        return $meta;
    }
}
