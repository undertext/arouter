<?php

namespace ARouter\Routing\HttpMessageConverter;

use ARouter\Routing\Exception\ApplicableConverterNotFoundException;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class HttpMessageConverterManagerUnitTest extends TestCase {

  /**
   * Get Request mock object.
   *
   * @param array $acceptHeader
   *   Accept header of request.
   *
   * @return \Mockery\MockInterface
   *   Request object mock.
   */
  private function getRequestMock(array $acceptHeader): MockInterface {
    $requestMock = Mockery::mock(ServerRequestInterface::class);
    $requestMock->shouldReceive('getHeader')->andReturn($acceptHeader);
    return $requestMock;
  }

  /**
   * @covers \ARouter\Routing\HttpMessageConverter\HttpMessageConverterManager
   * @covers \ARouter\Routing\Exception\ApplicableConverterNotFoundException
   */
  public function testConvertToResponse() {
    $converterDummy = Mockery::mock(HttpMessageConverterInterface::class);
    $converterDummy->shouldReceive('getFormats')->andReturn([]);

    $converter1 = Mockery::mock(HttpMessageConverterInterface::class);
    $converter1->shouldReceive('getFormats')->andReturn(['text/html']);
    $response1 = Mockery::mock(ResponseInterface::class);
    $converter1->shouldReceive('toResponse')->andReturn($response1);

    $converter2 = Mockery::mock(HttpMessageConverterInterface::class);
    $converter2->shouldReceive('getFormats')->andReturn(['application/json']);
    $response2 = Mockery::mock(ResponseInterface::class);
    $converter2->shouldReceive('toResponse')->andReturn($response2);

    $converterManager = new HttpMessageConverterManager();

    $request = $this->getRequestMock(['text/html']);
    try {
      $converterManager->convertToResponse('Something', $request);
      $this->fail();
    } catch (ApplicableConverterNotFoundException $ex) {
      $this->assertEquals($ex->getObjectForConversion(), 'Something');
    }

    $converterManager->addConverters([$converterDummy]);
    try {
      $converterManager->convertToResponse('Something', $request);
      $this->fail();
    } catch (ApplicableConverterNotFoundException $ex) {
      $this->assertEquals($ex->getObjectForConversion(), 'Something');
      $this->assertEquals($ex->getConverters(), [$converterDummy]);
    }

    $converterManager->addConverters([$converter1, $converter2]);
    $request = $this->getRequestMock(['text/html']);
    $response = $converterManager->convertToResponse('Something', $request);
    $this->assertEquals($response, $response1);

    $request = $this->getRequestMock(['application/json,some/some']);
    $response = $converterManager->convertToResponse('Something', $request);
    $this->assertEquals($response, $response2);
  }

}
