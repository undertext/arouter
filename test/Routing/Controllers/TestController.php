<?php

namespace ARouter\Routing\Controllers;


use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UploadedFileInterface;
use ARouter\Routing\Annotation\Controller;
use ARouter\Routing\Annotation\Route;

/**
 * @Controller
 */
class TestController {

  /**
   * @Route(path="testpath")
   */
  public function action($name, RequestInterface $request = NULL, UploadedFileInterface $file = NULL, $arg = NULL, $arg2 = 'defaultarg2') {
    echo $name;
  }

}
