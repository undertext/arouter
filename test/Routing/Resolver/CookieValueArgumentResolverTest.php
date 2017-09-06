<?php

namespace ARouter\Routing\Resolver;

use ARouter\Routing\RouteMapping;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use ARouter\Routing\Annotation\CookieValue;
use ARouter\Routing\Controllers\TestController;

/**
 * Tests CookieValueArgumentResolver class.
 */
class CookieValueArgumentResolverTest extends TestCase {

  /**
   * Tests resolve method.
   */
  public function testResolve() {
    $argumentResolver = new CookieValueArgumentResolver();
    $keyedMethodParams = [];
    $reflectionMethod = new \ReflectionMethod(TestController::class, 'action');
    foreach ($reflectionMethod->getParameters() as $methodParam) {
      $keyedMethodParams[$methodParam->name] = $methodParam;
    }
    $request = $this->createMock(ServerRequestInterface::class);
    $request->method('getCookieParams')->willReturn(['date' => '17-02-1993']);
    $requestBodyAnnotation = new CookieValue([
      'for' => 'arg',
      'from' => 'date'
    ]);

    $result = $argumentResolver->resolve($keyedMethodParams, new RouteMapping('request', TestController::class, 'requestParam', [$requestBodyAnnotation]), $request);
    self::assertEquals($result, ['arg' => '17-02-1993']);

  }

}
