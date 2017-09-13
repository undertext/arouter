<?php

namespace ARouter\Routing\Controllers;


use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
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
    return new class implements ResponseInterface {

      public function getProtocolVersion() {}

      public function withProtocolVersion($version) {}

      public function getHeaders() {}

      public function hasHeader($name) {}

      public function getHeader($name) {}

      public function getHeaderLine($name) {}

      public function withHeader($name, $value) {}

      public function withAddedHeader($name, $value) {}

      public function withoutHeader($name) {}

      public function getBody() {
        return "Test controller body";
      }

      public function withBody(StreamInterface $body) {}

      public function getStatusCode() {}

      public function withStatus($code, $reasonPhrase = '') {}

      public function getReasonPhrase() {}
    };
  }

}
