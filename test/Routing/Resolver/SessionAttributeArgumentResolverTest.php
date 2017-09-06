<?php

namespace ARouter\Routing\Resolver;

use ARouter\Routing\RouteMapping;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use ARouter\Routing\Annotation\SessionAttribute;
use ARouter\Routing\Controllers\TestController;

/**
 * Tests SessionAttributeArgumentResolver class.
 */
class SessionAttributeArgumentResolverTest extends TestCase {

  /**
   * Tests resolve method.
   */
  public function testResolve() {
    $argumentResolver = new SessionAttributeArgumentResolver();
    $keyedMethodParams = [];
    $reflectionMethod = new \ReflectionMethod(TestController::class, 'action');
    foreach ($reflectionMethod->getParameters() as $methodParam) {
      $keyedMethodParams[$methodParam->name] = $methodParam;
    }
    $request = $this->createMock(ServerRequestInterface::class);
    $_SESSION['arg'] = 3;
    $requestBodyAnnotation = new SessionAttribute(['for' => 'arg']);

    $result = $argumentResolver->resolve($keyedMethodParams, new RouteMapping('request', TestController::class, 'requestParam', [$requestBodyAnnotation]), $request);
    self::assertEquals($result, ['arg' => 3]);

  }


}