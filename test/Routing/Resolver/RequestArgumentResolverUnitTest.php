<?php

namespace ARouter\Routing\Resolver;

use ARouter\Routing\RouteMapping;
use ARouter\Routing\Controllers\TestController;

/**
 * Tests RequestArgumentResolver class.
 */
class RequestArgumentResolverUnitTest extends ArgumentResolverTestBase {

  /**
   * @covers \ARouter\Routing\Resolver\RequestArgumentResolver
   */
  public function testResolve() {
    $argumentResolver = new RequestArgumentResolver();
    $result = $argumentResolver->resolve($this->testControllerKeyedMethodParams, new RouteMapping('request', TestController::class, 'argumentResolversPath', []), $this->request);
    self::assertEquals($result, ['request' => $this->request]);
  }

}
