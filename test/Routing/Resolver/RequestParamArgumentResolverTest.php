<?php

namespace ARouter\Routing\Resolver;

use ARouter\Routing\RouteMapping;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UploadedFileInterface;
use ARouter\Routing\Annotation\RequestParam;
use ARouter\Routing\Controllers\TestController;

/**
 * Tests RequestParamArgumentResolver class.
 */
class RequestParamArgumentResolverTest extends TestCase {

  /**
   * Tests resolve method.
   */
  public function testResolve() {
    $argumentResolver = new RequestParamArgumentResolver();
    $file = self::createMock(UploadedFileInterface::class);
    $keyedMethodParams = [];
    $reflectionMethod = new \ReflectionMethod(TestController::class, 'action');
    foreach ($reflectionMethod->getParameters() as $methodParam) {
      $keyedMethodParams[$methodParam->name] = $methodParam;
    }
    $request = $this->createMock(ServerRequestInterface::class);
    $request->method('getQueryParams')->willReturn(['arg' => 'testarg']);
    $request->method('getUploadedFiles')->willReturn(['file' => $file]);
    $requestBodyAnnotation = new RequestParam(['for' => 'arg']);
    $requestBody2Annotation = new RequestParam(['for' => 'file']);

    $result = $argumentResolver->resolve($keyedMethodParams, new RouteMapping('request', TestController::class, 'action', [
      $requestBodyAnnotation,
      $requestBody2Annotation
    ]), $request);
    self::assertEquals($result, ['arg' => 'testarg', 'file' => $file]);
  }

}
