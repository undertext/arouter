<?php

namespace ARouter\Routing\Resolver;

use ARouter\Routing\Annotation\RequestBody;
use ARouter\Routing\RouteMapping;
use ARouter\Routing\Controllers\TestController;

/**
 * Tests RequestBodyArgumentResolver class.
 */
class RequestBodyResolverUnitTest extends ArgumentResolverTestBase {

  /**
   * @covers \ARouter\Routing\Resolver\RequestBodyArgumentResolver
   * @covers \ARouter\Routing\Annotation\RequestBody
   */
  public function testResolve() {
    $this->request->shouldReceive('getBody->__toString')->andReturn('Test request body value');
    $requestBodyAnnotation = new RequestBody();
    $requestBodyAnnotation->for = 'requestBody';

    $argumentResolver = new RequestBodyArgumentResolver();
    $result = $argumentResolver->resolve($this->testControllerKeyedMethodParams, new RouteMapping('request', TestController::class, 'argumentResolversPath', [$requestBodyAnnotation]), $this->request);
    self::assertEquals($result, ['requestBody' => 'Test request body value']);
  }

}
