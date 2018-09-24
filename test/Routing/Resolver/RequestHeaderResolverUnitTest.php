<?php

namespace ARouter\Routing\Resolver;

use ARouter\Routing\RouteMapping;
use ARouter\Routing\Annotation\RequestHeader;
use ARouter\Routing\Controllers\TestController;

/**
 * Tests RequestHeaderArgumentResolver class.
 */
class RequestHeaderResolverUnitTest extends ArgumentResolverTestBase {

  /**
   * @covers \ARouter\Routing\Resolver\RequestHeaderArgumentResolver
   * @covers \ARouter\Routing\Annotation\RequestHeader
   */
  public function testResolve() {
    $this->request->shouldReceive('getHeader')->andReturn('Mozilla/5.0');
    $requestHeaderAnnotation = new RequestHeader();
    $requestHeaderAnnotation->for = 'requestHeader';
    $requestHeaderAnnotation->from = 'User-Agent';

    $argumentResolver = new RequestHeaderArgumentResolver();
    $result = $argumentResolver->resolve($this->testControllerKeyedMethodParams, new RouteMapping('request', TestController::class, 'argumentResolversPath', [$requestHeaderAnnotation]), $this->request);
    self::assertEquals($result, ['requestHeader' => 'Mozilla/5.0']);
  }

}
