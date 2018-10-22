<?php

namespace ARouter\Routing\Controllers;

use ARouter\Routing\Annotation\RequestParam;
use ARouter\Routing\Annotation\Controller;
use ARouter\Routing\Annotation\Route;
use Mockery;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UploadedFileInterface;

/**
 * @Controller
 */
class TestController {

  /**
   * @Route(path="simple-action")
   */
  public function simpleAction() {
    $responseMock = Mockery::mock(ResponseInterface::class);
    $responseMock->shouldReceive('getBody')
      ->andReturn("This is a simple action");
    return $responseMock;
  }

  /**
   * @Route(path="parameter-in-path/{name}")
   */
  public function parameterInPathAction($name) {
    $responseMock = Mockery::mock(ResponseInterface::class);
    $responseMock->shouldReceive('getBody')
      ->andReturn("Given parameter is $name");
    return $responseMock;
  }

  /**
   * @Route(path="parameter-in-path/{name}")
   *
   * @RequestParam(for="arg1")
   * @RequestParam(for="arg2")
   */
  public function queryParamsAction($arg1, $arg2, $arg3 = 'default') {

  }

  public function argumentResolversPath($cookie, $requestBody, $requestHeader, $requestParam, UploadedFileInterface $file, $sessionAttribute, RequestInterface $request, $name, $section) {

  }

  public function converterAction() {
    return "string response";
  }

}
