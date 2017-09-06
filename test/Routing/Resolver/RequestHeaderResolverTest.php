<?php

namespace ARouter\Routing\Resolver;

use ARouter\Routing\RouteMapping;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use ARouter\Routing\Annotation\RequestHeader;
use ARouter\Routing\Controllers\TestController;

/**
 * Tests RequestHeaderArgumentResolver class.
 */
class RequestHeaderResolverTest extends TestCase {

  /**
   * Tests resolve method.
   */
  public function testResolve() {
    $argumentResolver = new RequestHeaderArgumentResolver();
    $keyedMethodParams = [];
    $reflectionMethod = new \ReflectionMethod(TestController::class, 'action');
    foreach ($reflectionMethod->getParameters() as $methodParam) {
      $keyedMethodParams[$methodParam->name] = $methodParam;
    }
    $request = $this->createMock(ServerRequestInterface::class);
    $request->method('getHeader')->willReturn('Mozilla/5.0');
    $requestBodyAnnotation = new RequestHeader([
      'for' => 'arg',
      'from' => 'User-Agent'
    ]);

    $result = $argumentResolver->resolve($keyedMethodParams, new RouteMapping('request', TestController::class, 'request', [$requestBodyAnnotation]), $request);
    self::assertEquals($result, ['arg' => 'Mozilla/5.0']);

  }


}