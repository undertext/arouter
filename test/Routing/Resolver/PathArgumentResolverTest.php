<?php

namespace ARouter\Routing\Resolver;

use ARouter\Routing\RouteMapping;
use Psr\Http\Message\UriInterface;
use ARouter\Routing\Controllers\TestController;

/**
 * Tests PathArgumentResolver class.
 */
class PathArgumentResolverTest extends ArgumentResolverTestBase {

  /**
   * Tests resolve method.
   */
  public function testResolve() {
    $uri = $this->createMock(UriInterface::class);
    $uri->method('getPath')->willReturn('user/testuser');
    $this->request->method('getUri')->willReturn($uri);

    $argumentResolver = new PathArgumentResolver();
    $result = $argumentResolver->resolve($this->testControllerKeyedMethodParams, new RouteMapping('user/{name}', TestController::class, 'username', []), $this->request);
    self::assertEquals($result, ['name' => 'testuser']);

  }

}
