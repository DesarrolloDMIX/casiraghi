<?php

namespace App\Middleware;

use Cake\Core\Configure;
use Psr\Http\Server\MiddlewareInterface;
use Cake\Http\Cookie\Cookie;
use Psr\Http\Server\RequestHandlerInterface;
use Cake\Utility\Security;
use DateTime;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class WsiCookieMiddleware implements MiddlewareInterface
{
    /**
     * Process Middleware.
     *
     * @param Cake\Http\ServerRequest $request 
     * @param RequestHandlerInterface $handler 
     * @return Cake\Http\Response|Psr\Http\Message\ResponseInterface;
     * @throws conditon
     **/
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {

        if ($request->getCookie('wsi') === null) {

            $value = Security::encrypt('{}', Configure::read('Security.cookieKey'));

            $value = base64_encode($value);

            $value = 'Q2FrZQ==.' . $value;

            $cookies = $request->getCookieCollection();

            $cookies = $cookies->add(
                new Cookie('wsi', $value, new DateTime('+1 year'))
            );

            $request = $request->withCookieCollection($cookies);

            $response = $handler->handle($request);

            if (!$response->getCookieCollection()->has('wsi')) {
                $cookies = $response->getCookieCollection();

                $cookies = $cookies->add(
                    new Cookie('wsi', $value, new DateTime('+1 year'))
                );

                $response = $response->withCookieCollection($cookies);
            }
        } else {
            $response = $handler->handle($request);
        }

        return $response;
    }
}
