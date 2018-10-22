<?php

namespace ARouter\Routing\Resolver;

use ARouter\Routing\RouteMapping;
use ARouter\Routing\Annotation\SessionAttribute;
use ARouter\Routing\Controllers\TestController;
use ARouter\Routing\RouteMatch;

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

    $routeMapping = new RouteMapping('request', TestController::class, 'argumentResolversPath', [$requestBodyAnnotation]);
    $routeMatch = new RouteMatch($routeMapping, $this->request, []);
    $argumentResolver = new SessionAttributeArgumentResolver();
    $result = $argumentResolver->resolve($this->testControllerKeyedMethodParams, $routeMatch);
    self::assertEquals($result, ['sessionAttribute' => 3]);
  }

}
