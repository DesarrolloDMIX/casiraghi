<?php

declare(strict_types=1);

namespace Wsi\PrestashopApi\Dmix;

use Wsi\PrestashopApi\Dmix\Api;

class Resource
{
    public $resourceName;

    protected $api;

    public function __construct(string $resourceName)
    {
        $this->resourceName = $resourceName;

        $this->api = new Api();
    }

    public function get(int $id, array $modifiers = [])
    {
        $options = [];
        $options['id'] = $id;
        $options['modifiers'] = $modifiers;

        return $this->api->bring($this->resourceName, $options);
    }

    public function getAll(array $modifiers = [])
    {
        $options = [];
        $options['with'] = $modifiers;
        return $this->api->bring($this->resourceName, $options);
    }
}
