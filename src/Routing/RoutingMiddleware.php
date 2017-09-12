<?php

namespace ARouter\Routing;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;

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
  public function process(ServerRequestInterface $request, DelegateInterface $delegate) {
    $routeHandler = $this->router->getRouteHandler($request);
    if ($routeHandler !== NULL) {
      $response = $routeHandler->execute();
      return $response;
    }
  }
}
