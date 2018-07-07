<?php

namespace ARouter\Routing;

use Interop\Http\ServerMiddleware\DelegateInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Tests RoutingMiddleware functionality.
 */
class RoutingMiddlewareTest extends TestCase {

  /**
   * Tests process method.
   */
  public function testRoutingMiddleware() {
    $request = $this->createMock(ServerRequestInterface::class);
    $response = $this->createMock(ResponseInterface::class);
    $delegate = $this->createMock(DelegateInterface::class);
    $routeHandler = $this->createMock(RouteHandler::class);
    $routeHandler->method('execute')->willReturn($response);
    $router = $this->createMock(Router::class);
    $router->method('getResponse')->willReturn($response);
    $routingMiddleware = new RoutingMiddleware($router);

    $result = $routingMiddleware->process($request, $delegate);
    self::assertEquals($result, $response);
  }

}
