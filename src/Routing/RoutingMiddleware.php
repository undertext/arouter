<?php

namespace ARouter\Routing;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * @addtogroup middleware
 *
 * ## PSR-15 middleware
 * You can use this router as PSR-15 middleware.
 *
 * Example with https://github.com/procurios/middleware-dispatcher :
 * ```php
 * $dispatcher = (new Dispatcher())
 * ->withMiddleware(new RoutingMiddleware($router));
 * $response = $dispatcher->process($request);
 * ```
 */
class RoutingMiddleware implements MiddlewareInterface {

  /**
   * Router object.
   *
   * @var \ARouter\Routing\Router
   */
  private $router;

  /**
   * RoutingMiddleware constructor.
   *
   * @param \ARouter\Routing\Router $router
   *   Router object.
   */
  public function __construct($router) {
    $this->router = $router;
  }

  /**
   * {@inheritdoc}
   */
  public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface {
    return $this->router->getResponse($request);
  }
}
