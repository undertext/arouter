<?php

namespace ARouter\Routing\Resolver;

use ARouter\Routing\RouteMapping;
use ARouter\Routing\Annotation\CookieValue;
use ARouter\Routing\Controllers\TestController;

/**
 * Tests CookieValueArgumentResolver class.
 */
class CookieValueArgumentResolverUnitTest extends ArgumentResolverTestBase {

  /**
   * @covers \ARouter\Routing\Resolver\CookieValueArgumentResolver
   * @covers \ARouter\Routing\Annotation\CookieValue
   */
  public function testResolve() {
    $this->request->shouldReceive('getCookieParams')
      ->andReturn(['date' => '17-02-1993']);
    $cookieValueAnnotation = new CookieValue([
      'for' => 'cookie',
      'from' => 'date',
    ]);

    $argumentResolver = new CookieValueArgumentResolver();
    $result = $argumentResolver->resolve($this->testControllerKeyedMethodParams, new RouteMapping('request', TestController::class, 'argumentResolversPath', [$cookieValueAnnotation]), $this->request);
    self::assertEquals($result, ['cookie' => '17-02-1993']);
  }

}
