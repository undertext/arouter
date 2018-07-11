<?php

namespace ARouter\Routing\Resolver;

use ARouter\Routing\RouteMapping;
use ARouter\Routing\Annotation\RequestHeader;
use ARouter\Routing\Controllers\TestController;

/**
 * Tests RequestHeaderArgumentResolver class.
 */
class RequestHeaderResolverTest extends ArgumentResolverTestBase {

  /**
   * Tests resolve method.
   */
  public function testResolve() {
    $this->request->method('getHeader')->willReturn('Mozilla/5.0');
    $requestBodyAnnotation = new RequestHeader([
      'for' => 'arg',
      'from' => 'User-Agent',
    ]);

    $argumentResolver = new RequestHeaderArgumentResolver();
    $result = $argumentResolver->resolve($this->testControllerKeyedMethodParams, new RouteMapping('request', TestController::class, 'request', [$requestBodyAnnotation]), $this->request);
    self::assertEquals($result, ['arg' => 'Mozilla/5.0']);

  }

}
