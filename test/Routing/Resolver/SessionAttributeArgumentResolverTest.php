<?php

namespace ARouter\Routing\Resolver;

use ARouter\Routing\RouteMapping;
use ARouter\Routing\Annotation\SessionAttribute;
use ARouter\Routing\Controllers\TestController;

/**
 * Tests SessionAttributeArgumentResolver class.
 */
class SessionAttributeArgumentResolverTest extends ArgumentResolverTestBase {

  /**
   * Tests resolve method.
   */
  public function testResolve() {
    $_SESSION['arg'] = 3;
    $requestBodyAnnotation = new SessionAttribute(['for' => 'arg']);

    $argumentResolver = new SessionAttributeArgumentResolver();
    $result = $argumentResolver->resolve($this->testControllerKeyedMethodParams, new RouteMapping('request', TestController::class, 'requestParam', [$requestBodyAnnotation]), $this->request);
    self::assertEquals($result, ['arg' => 3]);

  }

}
