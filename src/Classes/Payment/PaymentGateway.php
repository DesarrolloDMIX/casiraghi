<?php

declare(strict_types=1);

namespace Wsi\Payments;

use Cake\Core\Configure;
use Decidir\Connector;

class PaymentGateway
{
    protected $currency;

    protected $decidir;

    protected $decidir_keys = [
        'public_key' => '96e7f0d36a0648fb9a8dcb50ac06d260',
        'private_key' => '1b19bb47507c4a259ca22c12f78e881f',
    ];

    public function __construct(String $currency = 'ARS')
    {
        $this->decidir = new Connector($this->decidir_keys, Configure::read('debug') ? 'test' : 'prod');

        $this->currency = $currency;
    }
}
