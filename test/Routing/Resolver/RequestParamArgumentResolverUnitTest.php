<?php

namespace ARouter\Routing\Resolver;

use ARouter\Routing\RouteMapping;
use ARouter\Routing\RouteMatch;
use Mockery;
use Psr\Http\Message\UploadedFileInterface;
use ARouter\Routing\Annotation\RequestParam;
use ARouter\Routing\Controllers\TestController;

/**
 * Tests RequestParamArgumentResolver class.
 */
class RequestParamArgumentResolverUnitTest extends ArgumentResolverTestBase {

  /**
   * @covers \ARouter\Routing\Resolver\RequestParamArgumentResolver
   * @covers \ARouter\Routing\Annotation\RequestParam
   */
  public function testResolve() {
    $file = Mockery::mock(UploadedFileInterface::class);

    $this->request->shouldReceive('getQueryParams')->andReturn(['requestParam' => 'testarg']);
    $this->request->shouldReceive('getUploadedFiles')->andReturn(['file' => $file]);
    $requestBodyAnnotation = new RequestParam(['for' => 'requestParam']);
    $requestBody2Annotation = new RequestParam(['for' => 'file']);

    $routeMapping = new RouteMapping('request', TestController::class, 'argumentResolversPath', [
      $requestBodyAnnotation,
      $requestBody2Annotation,
    ]);
    $routeMatch = new RouteMatch($routeMapping, $this->request, []);
    $argumentResolver = new RequestParamArgumentResolver();
    $result = $argumentResolver->resolve($this->testControllerKeyedMethodParams, $routeMatch);
    self::assertEquals($result, ['requestParam' => 'testarg', 'file' => $file]);
  }

}
