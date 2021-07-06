<?php

namespace App\Middleware;

use Cake\Core\Configure;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Cake\Utility\Security;
use Wsi\Utils\AdminAuth;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class AdminMiddleware implements MiddlewareInterface
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
        $isAdmin = AdminAuth::checkIsAdmin($request->getCookieParams()['wsiA'] ?? 'notAdmin');
        $isLoggingIn = $request->getUri()->getPath() === '/admin/login';

        if ((!$isAdmin) && (!$isLoggingIn)) {
            header('Location: /admin/login?path=' . $request->getUri()->getPath());
            die;
        }

        $response = $handler->handle($request);
        return $response;
    }
}
