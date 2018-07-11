<?php

namespace ARouter\Routing\Resolver;

use ARouter\Routing\RouteMapping;
use ARouter\Routing\Controllers\TestController;

/**
 * Tests RequestArgumentResolver class.
 */
class RequestArgumentResolverTest extends ArgumentResolverTestBase {

  /**
   * Tests resolve method.
   */
  public function testResolve() {
    $argumentResolver = new RequestArgumentResolver();
    $result = $argumentResolver->resolve($this->testControllerKeyedMethodParams, new RouteMapping('request', TestController::class, 'action', []), $this->request);
    self::assertEquals($result, ['request' => $this->request]);
  }

}
