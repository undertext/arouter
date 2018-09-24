<?php

namespace ARouter\Routing\Resolver;

use ARouter\Routing\RouteMapping;
use ARouter\Routing\Controllers\TestController;

/**
 * Tests PathArgumentResolver class.
 */
class PathArgumentResolverTest extends ArgumentResolverTestBase {

  /**
   * @covers \ARouter\Routing\Resolver\PathArgumentResolver
   */
  public function testResolve() {
    $this->request->shouldReceive('getUri->getPath')->andReturn('user/testuser');

    $argumentResolver = new PathArgumentResolver();
    $result = $argumentResolver->resolve($this->testControllerKeyedMethodParams, new RouteMapping('user/{name}', TestController::class, 'argumentResolversPath', []), $this->request);
    self::assertEquals($result, ['name' => 'testuser']);
  }

}
