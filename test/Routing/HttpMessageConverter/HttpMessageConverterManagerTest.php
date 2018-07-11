<?php

namespace ARouter\Routing\HttpMessageConverter;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ServerRequestInterface;

class HttpMessageConverterManagerTest extends TestCase {

  public function testGetApplicableConverter() {
    $converter1 = $this->createMock(HttpMessageConverterInterface::class);
    $converter1->method('getFormats')->willReturn(['text/html']);

    $converter2 = $this->createMock(HttpMessageConverterInterface::class);
    $converter2->method('getFormats')->willReturn(['application/json']);

    $converterManager = new HttpMessageConverterManager();

    $request = $this->getRequestMock(['text/html']);
    $converter = $converterManager->getApplicableConverter($request);
    $this->assertEquals($converter, NULL);

    $converterManager->addConverter($converter1);
    $converterManager->addConverter($converter2);

    $request = $this->getRequestMock(['text/html']);
    $converter = $converterManager->getApplicableConverter($request);
    $this->assertEquals($converter, $converter1);

    $request = $this->getRequestMock(['application/json,some/some']);
    $converter = $converterManager->getApplicableConverter($request);
    $this->assertEquals($converter, $converter2);

  }

  /**
   * Get Request mock object.
   *
   * @param string $path
   *   Request path.
   *
   * @return \Psr\Http\Message\RequestInterface
   */
  private function getRequestMock($acceptHeader): RequestInterface {
    $request = $this->createMock(ServerRequestInterface::class);
    $request->method('getHeader')->willReturn($acceptHeader);
    return $request;
  }

}
