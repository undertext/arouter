<?php

namespace ARouter\Routing\Resolver;

use ARouter\Routing\RouteMapping;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;
use ARouter\Routing\Controllers\TestController;

/**
 * Tests PathArgumentResolver class.
 */
class PathArgumentResolverTest extends TestCase {

  /**
   * Tests resolve method.
   */
  public function testResolve() {
    $argumentResolver = new PathArgumentResolver();
    $keyedMethodParams = [];
    $reflectionMethod = new \ReflectionMethod(TestController::class, 'action');
    foreach ($reflectionMethod->getParameters() as $methodParam) {
      $keyedMethodParams[$methodParam->name] = $methodParam;
    }
    $request = $this->createMock(ServerRequestInterface::class);
    $uri = $this->createMock(UriInterface::class);
    $uri->method('getPath')->willReturn('user/testuser');
    $request->method('getUri')->willReturn($uri);

    $result = $argumentResolver->resolve($keyedMethodParams, new RouteMapping('user/{name}', TestController::class, 'username', []), $request);

    self::assertEquals($result, ['name' => 'testuser']);

  }


}