<?php

namespace ARouter\Routing;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * PSR-15 middleware for routing.
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
