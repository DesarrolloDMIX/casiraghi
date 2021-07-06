<?php

/**
 * Routes configuration.
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
 *
 * It's loaded within the context of `Application::routes()` method which
 * receives a `RouteBuilder` instance `$routes` as method argument.
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

use App\Middleware\WsiCookieMiddleware;
use Cake\Core\Configure;
use Cake\Http\Middleware\CsrfProtectionMiddleware;
use Cake\Http\Middleware\EncryptedCookieMiddleware;
use App\Middleware\AdminMiddleware;
use Cake\Routing\Route\DashedRoute;
use Cake\Routing\RouteBuilder;

/*
 * The default class to use for all routes
 *
 * The following route classes are supplied with CakePHP and are appropriate
 * to set as the default:
 *
 * - Route
 * - InflectedRoute
 * - DashedRoute
 *
 * If no call is made to `Router::defaultRouteClass()`, the class used is
 * `Route` (`Cake\Routing\Route\Route`)
 *
 * Note that `Route` does not do any inflections on URLs which will result in
 * inconsistently cased URLs when used with `:plugin`, `:controller` and
 * `:action` markers.
 */

/** @var \Cake\Routing\RouteBuilder $routes */
$routes->setRouteClass(DashedRoute::class);

$routes->scope('/', function (RouteBuilder $builder) {
    $builder->registerMiddleware('wsiCookie', new WsiCookieMiddleware());

    $builder->registerMiddleware('csrf', new CsrfProtectionMiddleware([
        'httpOnly' => true,
    ]));

    $builder->registerMiddleware('cookie', new EncryptedCookieMiddleware(
        ['wsi', 'admin_key'],
        Configure::read('Security.cookieKey')
    ));

    $builder->applyMiddleware('wsiCookie', 'csrf', 'cookie');

    // ROUTES

    $builder->connect('/', ['controller' => 'Home', 'action' => 'display']);

    $builder->connect(
        '/categorias/{gsrId}',
        ['controller' => 'Categories', 'action' => 'displayProductsOfCategory']
    )
        ->setPass(['gsrId']);

    $builder->connect(
        '/categorias/{gsrId}/{srId}',
        ['controller' => 'Categories', 'action' => 'displayProductsOfCategory']
    )
        ->setPass(['gsrId', 'srId']);

    $builder->connect(
        '/categorias/{gsrId}/{srId}/{rId}',
        ['controller' => 'Categories', 'action' => 'displayProductsOfCategory']
    )
        ->setPass(['gsrId', 'srId', 'rId']);

    $builder->connect(
        '/productos/{productId}',
        ['controller' => 'Products', 'action' => 'displayProduct']
    )
        ->setPass(['productId']);

    $builder->connect(
        '/carrito',
        ['controller' => 'Cart', 'action' => 'displayCart']
    );

    $builder->post(
        '/carrito/add-to-cart',
        ['controller' => 'Cart', 'action' => 'addToCart']
    );

    $builder->connect(
        '/carrito/delete-item/{productId}',
        ['controller' => 'Cart', 'action' => 'deleteItem']
    )
        ->setPass(['productId']);

    $builder->get(
        '/carrito/to-checkout',
        ['controller' => 'Cart', 'action' => 'toCheckout']
    );

    $builder->post(
        '/carrito/apply-discount',
        ['controller' => 'Cart', 'action' => 'applyDiscount']
    );

    $builder->post(
        '/contacto/enviar-mensaje',
        ['controller' => 'Contact', 'action' => 'send']
    );

    // STATIC PAGES

    // $builder->connect('/*', ['controller' => 'Pages', 'action' => 'display']);

    // CHECKOUT ROUTES

    $builder->scope('/pagar', ['controller' => 'Checkout'], function (RouteBuilder $builder) {
        $builder->connect(
            '/checkout',
            ['action' => 'displayCheckout']
        );

        $builder->connect(
            '/link/{token}',
            ['action' => 'displayFormCardDetails']
        )
            ->setPass(['token']);

        $builder->connect(
            '/link/pago',
            ['action' => 'processPayment']
        )
            ->setPass(['token']);

        $builder->connect(
            '/gracias',
            ['action' => 'displayThankyouPage']
        );
    });

    $builder->fallbacks();
});

$routes->prefix('admin', ['controller' => 'Admin'], function (RouteBuilder $builder) {

    $builder->registerMiddleware('admin', new AdminMiddleware());
    $builder->applyMiddleware('admin');

    $builder->connect(
        '/generar-link-pago',
        ['action' => 'createPaymentLink']
    );

    $builder->connect(
        '/login',
        ['action' => 'login']
    );

    $builder->connect(
        '/crear-firma',
        ['action' => 'createSignature']
    );

    $builder->connect(
        '/sincro-flexxus',
        ['action' => 'syncFlexxusForm']
    );

    $builder->scope('/presta-sync', ['controller' => 'PrestaSync'], function (RouteBuilder $builder) {

        $builder->connect('/');

        $builder->connect(
            '/bring/{resourceName}/{id}',
            ['action' => 'bring']
        )
            ->setPass(['resourceName', 'id']);

        $builder->connect(
            '/process-products-meta',
            ['action' => 'processProductsMeta']
        );

        $builder->connect(
            '/{action}'
        );
    });
});
