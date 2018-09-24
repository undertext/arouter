<?php

namespace ARouter\Routing\Resolver;

use ARouter\Routing\RouteMapping;
use ARouter\Routing\Annotation\SessionAttribute;
use ARouter\Routing\Controllers\TestController;

/**
 * Tests SessionAttributeArgumentResolver class.
 */
class SessionAttributeArgumentResolverUnitTest extends ArgumentResolverTestBase {

  /**
   * @covers \ARouter\Routing\Resolver\SessionAttributeArgumentResolver
   * @covers \ARouter\Routing\Annotation\SessionAttribute
   */
  public function testResolve() {
    $_SESSION['sessionAttribute'] = 3;
    $requestBodyAnnotation = new SessionAttribute(['for' => 'sessionAttribute']);

    $argumentResolver = new SessionAttributeArgumentResolver();
    $result = $argumentResolver->resolve($this->testControllerKeyedMethodParams, new RouteMapping('request', TestController::class, 'argumentResolversPath', [$requestBodyAnnotation]), $this->request);
    self::assertEquals($result, ['sessionAttribute' => 3]);
  }

}
