<?php

use Cake\Routing\RouteBuilder;

/** @var \Cake\Routing\RouteBuilder $routes */
//testing scopes

$routes->scope('/test-api', ['controller' => 'TestApi'], function (RouteBuilder $builder) {
    $builder->connect(
        '/request',
        ['action' => 'request']
    );

    $builder->connect(
        '/add',
        ['action' => 'add']
    );

    $builder->connect(
        '/check',
        ['action' => 'checkOut']
    );

    $builder->connect(
        '/create-account',
        ['action' => 'createAccount']
    );

    $builder->connect(
        '/create-cart',
        ['action' => 'createCart']
    );

    $builder->connect(
        '/redirect',
        ['action' => 'redirectToPs']
    );

    $builder->connect(
        '/redirect-cart',
        ['action' => 'redirectToPsWithCart']
    );

    $builder->connect(
        '/create-cart',
        ['action' => 'createCart']
    );

    $builder->connect(
        '/test-images',
        ['action' => 'testImages']
    );

    $builder->connect(
        '/synopsis/{resource}',
        ['action' => 'synopsis']
    )->setPass(['resource']);

    $builder->connect(
        '/{resource}'
    )->setPass(['resource']);

    $builder->connect(
        '/{resource}/{id}'
    )->setPass(['resource', 'id']);
});

$routes->scope('/test-decidir', ['controller' => 'TestDecidir'], function (RouteBuilder $builder) {
    $builder->connect(
        '/create-token',
        ['action' => 'createToken']
    );

    $builder->post(
        '/execute-payment',
        ['action' => 'executePayment']
    );
});
