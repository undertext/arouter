<?php

namespace ARouter\Routing\Resolver;

use ARouter\Routing\RouteMapping;
use ARouter\Routing\Controllers\TestController;
use ARouter\Routing\RouteMatch;

/**
 * Tests PathArgumentResolver class.
 */
class PathArgumentResolverTest extends ArgumentResolverTestBase {

  /**
   * @covers \ARouter\Routing\Resolver\PathArgumentResolver
   */
  public function testResolve() {
    $this->request->shouldReceive('getUri->getPath')->andReturn('user/testuser/testsection');

    $argumentResolver = new PathArgumentResolver();
    $routeMapping = new RouteMapping('user/{name}/{section}', TestController::class, 'argumentResolversPath', []);
    $routeMatch = new RouteMatch($routeMapping, $this->request, ['name' => 'testuser', 'section' => 'testsection']);
    $result = $argumentResolver->resolve($this->testControllerKeyedMethodParams, $routeMatch);
    self::assertEquals($result, ['name' => 'testuser', 'section' => 'testsection']);
  }

}
