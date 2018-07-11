<?php

namespace ARouter\Routing\Resolver;

use ARouter\Routing\RouteMapping;
use ARouter\Routing\Annotation\CookieValue;
use ARouter\Routing\Controllers\TestController;

/**
 * Tests CookieValueArgumentResolver class.
 */
class CookieValueArgumentResolverTest extends ArgumentResolverTestBase {

  /**
   * Tests resolve method.
   */
  public function testResolve() {
    $this->request->method('getCookieParams')
      ->willReturn(['date' => '17-02-1993']);
    $requestBodyAnnotation = new CookieValue([
      'for' => 'arg',
      'from' => 'date',
    ]);

    $argumentResolver = new CookieValueArgumentResolver();
    $result = $argumentResolver->resolve($this->testControllerKeyedMethodParams, new RouteMapping('request', TestController::class, 'requestParam', [$requestBodyAnnotation]), $this->request);
    self::assertEquals($result, ['arg' => '17-02-1993']);
  }

}
