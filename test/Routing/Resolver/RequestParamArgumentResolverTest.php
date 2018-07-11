<?php

namespace ARouter\Routing\Resolver;

use ARouter\Routing\RouteMapping;
use Psr\Http\Message\UploadedFileInterface;
use ARouter\Routing\Annotation\RequestParam;
use ARouter\Routing\Controllers\TestController;

/**
 * Tests RequestParamArgumentResolver class.
 */
class RequestParamArgumentResolverTest extends ArgumentResolverTestBase {

  /**
   * Tests resolve method.
   */
  public function testResolve() {
    $file = self::createMock(UploadedFileInterface::class);

    $this->request->method('getQueryParams')->willReturn(['arg' => 'testarg']);
    $this->request->method('getUploadedFiles')->willReturn(['file' => $file]);
    $requestBodyAnnotation = new RequestParam(['for' => 'arg']);
    $requestBody2Annotation = new RequestParam(['for' => 'file']);

    $argumentResolver = new RequestParamArgumentResolver();
    $result = $argumentResolver->resolve($this->testControllerKeyedMethodParams, new RouteMapping('request', TestController::class, 'action', [
      $requestBodyAnnotation,
      $requestBody2Annotation,
    ]), $this->request);
    self::assertEquals($result, ['arg' => 'testarg', 'file' => $file]);
  }

}
