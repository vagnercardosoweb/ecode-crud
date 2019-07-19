<?php

/*
 * VCWeb Networks <https://www.vcwebnetworks.com.br/>
 *
 * @author Vagner Cardoso <vagnercardosoweb@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 18/07/2019 Vagner Cardoso
 */

namespace App\Middlewares;

use Core\Router;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Class AuthMiddleware.
 *
 * @property \App\Models\Admin auth
 *
 * @author Vagner Cardoso <vagnercardosoweb@gmail.com>
 */
class AuthMiddleware extends Middleware
{
    /**
     * @param \Psr\Http\Message\RequestInterface                      $request  PSR7 request
     * @param \Psr\Http\Message\ResponseInterface|\Slim\Http\Response $response PSR7 response
     * @param callable                                                $next     Next middleware
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(RequestInterface $request, ResponseInterface $response, callable $next)
    {
        if (!$this->auth) {
            return $response->withRedirect(
                Router::pathFor('web.index')
            );
        }

        return $next($request, $response);
    }
}
