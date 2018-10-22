<?php

namespace ARouter\Routing\Resolver;

use ARouter\Routing\RouteMapping;
use ARouter\Routing\Controllers\TestController;
use ARouter\Routing\RouteMatch;

/**
 * Tests RequestArgumentResolver class.
 */
class RequestArgumentResolverUnitTest extends ArgumentResolverTestBase {

  /**
   * @covers \ARouter\Routing\Resolver\RequestArgumentResolver
   */
  public function testResolve() {
    $argumentResolver = new RequestArgumentResolver();
    $routeMapping = new RouteMapping('request', TestController::class, 'argumentResolversPath', []);
    $routeMatch = new RouteMatch($routeMapping, $this->request, []);
    $result = $argumentResolver->resolve($this->testControllerKeyedMethodParams, $routeMatch);
    self::assertEquals($result, ['request' => $this->request]);
  }

}
