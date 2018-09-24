<?php

namespace ARouter\Routing;

use ARouter\Routing\Annotation\Route;
use ARouter\Routing\Controllers\TestController;
use PHPUnit\Framework\TestCase;

/**
 * Tests RouteMapping class.
 */
class RouteMappingUnitTest extends TestCase {

  /**
   * @covers \ARouter\Routing\RouteMapping
   */
  public function testRouteMapping() {
    $route = new Route();
    $routeMapping = new RouteMapping('path', TestController::class, 'action', [$route]);

    $this->assertEquals($routeMapping->getAnnotations(), [$route]);
    $this->assertEquals($routeMapping->getMethod(), 'action');
    $this->assertEquals($routeMapping->getController(), TestController::class);
    $this->assertEquals($routeMapping->getPath(), 'path');
  }

}
