<?php

namespace ARouter\Routing\Resolver;

use ARouter\Routing\RouteMapping;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use ARouter\Routing\Controllers\TestController;

/**
 * Tests RequestArgumentResolver class.
 */
class RequestArgumentResolverTest extends TestCase {

  /**
   * Tests resolve method.
   */
  public function testResolve() {
    $argumentResolver = new RequestArgumentResolver();
    $keyedMethodParams = [];
    $reflectionMethod = new \ReflectionMethod(TestController::class, 'action');
    foreach ($reflectionMethod->getParameters() as $methodParam) {
      $keyedMethodParams[$methodParam->name] = $methodParam;
    }
    $request = $this->createMock(ServerRequestInterface::class);

    $result = $argumentResolver->resolve($keyedMethodParams, new RouteMapping('request', TestController::class, 'action', []), $request);
    self::assertEquals($result, ['request' => $request]);

  }


}