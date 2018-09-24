<?php

namespace ARouter\Routing\Controllers\SubDirectory;

use ARouter\Routing\Annotation\Controller;
use ARouter\Routing\Annotation\Route;
use Mockery;
use Psr\Http\Message\ResponseInterface;

/**
 * @Controller
 */
class TestController2 {

  /**
   * @Route(path="simple-action-2")
   */
  public function simpleAction2() {
    $responseMock = Mockery::mock(ResponseInterface::class);
    $responseMock->shouldReceive('getBody')
      ->andReturn("Simple action 2");
    return $responseMock;
  }

}
