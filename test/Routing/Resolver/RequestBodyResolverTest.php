<?php

namespace ARouter\Routing\Resolver;

use ARouter\Routing\RouteMapping;
use Psr\Http\Message\StreamInterface;
use ARouter\Routing\Annotation\RequestBody;
use ARouter\Routing\Controllers\TestController;

/**
 * Tests RequestBodyArgumentResolver class.
 */
class RequestBodyResolverTest extends ArgumentResolverTestBase {

  /**
   * Tests resolve method.
   */
  public function testResolve() {
    $stream = $this->createMock(StreamInterface::class);
    $this->request->method('getBody')->willReturn($stream);
    $stream->method('__toString')->willReturn('Test request body value');
    $requestBodyAnnotation = new RequestBody();
    $requestBodyAnnotation->for = 'arg';

    $argumentResolver = new RequestBodyArgumentResolver();
    $result = $argumentResolver->resolve($this->testControllerKeyedMethodParams, new RouteMapping('request', TestController::class, 'request', [$requestBodyAnnotation]), $this->request);
    self::assertEquals($result, ['arg' => 'Test request body value']);
  }

}
