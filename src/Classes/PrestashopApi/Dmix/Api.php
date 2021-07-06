<?php

declare(strict_types=1);

namespace Wsi\PrestashopApi\Dmix;

use Cake\Cache\Cache;
use Cake\Collection\Collection;
use Cake\Core\Configure;
use Cake\Http\Client;
use Cake\ORM\TableRegistry;
use Cake\Utility\Inflector;

class Api
{
    private $config;

    private $cacheConfig = 'apiResultsLong';

    private $cacheResult = false;

    private $cachedData = null;

    private $dataToCache = [];

    private $client;

    private $resourceName;

    private $contains;

    private $dmixApiUrl = '/module/dmixapi';

    public function __construct()
    {
        $this->config = include(__DIR__ . '/config.php');

        $prestashopUrl = explode('://', Configure::read('Prestashop.url'));
        $prestashopHost = $prestashopUrl[1];
        $prestashopScheme = $prestashopUrl[0];
        $this->client = new Client([
            'host' => $prestashopHost . $this->dmixApiUrl,
            'scheme' => $prestashopScheme
        ]);
    }

    public function connect(string $resourceName, $options = [])
    {
        $this->resourceName = $resourceName;
        $this->options = $options;

        return $this;
    }

    public function addContains(string $resourceName)
    {
        if (!array_key_exists($resourceName, $this->config['resources'][$this->resourceName]['contains'])) {
            return $this;
        }

        if (isset($this->cachedData['contains'][$resourceName])) {
            $resource = $this->cachedData['contains'][$resourceName];
        } else {
            $resource = new Collection(json_decode($this->client->get("/$resourceName")->getBody()->getContents(), true));

            $resource = $resource->indexBy('id');
        }

        if ($this->cacheResult) {
            $this->dataToCache['contains'][$resourceName] = $resource;
        }

        $this->contains[$resourceName] = $resource;
        $this->options['modifiers'][] = $resourceName;

        return $this;
    }

    public function get()
    {
        $resource = $this->bring($this->resourceName, $this->options);

        if ($this->contains) {
            foreach ($this->contains as $containsName => $containsResource) {
                $containsRules = $this->config['resources'][$this->resourceName]['contains'][$containsName];

                $resource = $resource->map(function ($record) use ($containsResource, $containsName, $containsRules) {
                    $parentKey = $containsRules['parent_key'];
                    if ($record[$parentKey]) {
                        $containsIds = explode(',', $record[$parentKey]);
                        $recordContains = array_intersect_key($containsResource->toArray(), array_flip($containsIds));

                        $record[$containsRules['field_name']] = $recordContains;
                        return $record;
                    }
                    return $record;
                });
            }
        }

        if ($this->cacheResult) {
            Cache::write($this->cacheKey, $this->dataToCache, $this->cacheConfig);
        }

        return $resource;
    }

    public function cache(string $key)
    {
        $this->cachedData = Cache::read($key, $this->cacheConfig);

        $this->cacheResult = true;

        $this->cacheKey = $key;

        return $this;
    }

    public function bring(string $resourceName, array $options = [])
    {
        if (isset($this->cachedData['resource'])) {
            $resource = $this->cachedData['resource'];
        } else {
            foreach ($options as $key => $option) {
                if (is_array($option)) {
                    $options[$key] = serialize($option);
                }
            }

            $resource = json_decode($this->client->get("/$resourceName", $options)->getBody()->getContents(), TRUE);
        }

        if ($this->cacheResult) {
            $this->dataToCache['resource'] = $resource;
        }

        if ($resource) {
            $resource = TableRegistry::getTableLocator()->get(Inflector::pluralize(Inflector::classify($resourceName)))->newEntities($resource);
        } else {
            $resource = [];
        }

        return new Collection($resource);
    }
}
