<?php

namespace ARouter\Routing\Resolver;

use ARouter\Routing\RouteMapping;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use ARouter\Routing\Annotation\RequestBody;
use ARouter\Routing\Controllers\TestController;

/**
 * Tests RequestBodyArgumentResolver class.
 */
class RequestBodyResolverTest extends TestCase {

  /**
   * Tests resolve method.
   */
  public function testResolve() {
    $argumentResolver = new RequestBodyArgumentResolver();
    $keyedMethodParams = [];
    $reflectionMethod = new \ReflectionMethod(TestController::class, 'action');
    foreach ($reflectionMethod->getParameters() as $methodParam) {
      $keyedMethodParams[$methodParam->name] = $methodParam;
    }
    $stream = $this->createMock(StreamInterface::class);
    $request = $this->createMock(ServerRequestInterface::class);
    $request->method('getBody')->willReturn($stream);
    $stream->method('__toString')->willReturn('Test request body value');
    $requestBodyAnnotation = new RequestBody();
    $requestBodyAnnotation->for = 'arg';

    $result = $argumentResolver->resolve($keyedMethodParams, new RouteMapping('request', TestController::class, 'request', [$requestBodyAnnotation]), $request);
    self::assertEquals($result, ['arg' => 'Test request body value']);
  }

}
