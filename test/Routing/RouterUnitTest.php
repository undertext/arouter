<?php

namespace ARouter\Routing;

use ARouter\Routing\Exception\RouteHandlerNotFoundException;
use ARouter\Routing\HttpMessageConverter\HttpMessageConverterManager;
use ARouter\Routing\Resolver\Service\MethodArgumentsResolverService;
use ARouter\Routing\Scanner\AnnotationRouteMappingsScanner;
use Mockery;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use ARouter\Routing\Controllers\TestController;

/**
 * Tests Router functionality.
 */
class RouterUnitTest extends TestCase {

  /**
   * Router object that will be tested.
   *
   * @var \ARouter\Routing\Router
   */
  private $router;

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    $scannerMock = Mockery::mock(AnnotationRouteMappingsScanner::class);
    $routeMapping1 = new RouteMapping('parameter-in-path/{name}', TestController::class, 'parameterInPathAction');
    $routeMapping2 = new RouteMapping('simple-action', TestController::class, 'simpleAction');
    $routeMapping3 = new RouteMapping('converter-action', TestController::class, 'converterAction');
    $routeMappings = [$routeMapping1, $routeMapping2, $routeMapping3];
    $scannerMock->shouldReceive('discoverRouteMappings')
      ->andReturn($routeMappings);

    $argumentsResolverServiceMock = Mockery::mock(MethodArgumentsResolverService::class);
    $argumentsResolverServiceMock->shouldReceive('resolveArguments')
      ->andReturn(['name' => 'nameValue']);

    $converterManagerMock = Mockery::mock(HttpMessageConverterManager::class);
    $converterManagerMock->shouldReceive('convertToResponse')
      ->andReturnUsing(function ($body) {
        $responseMock = Mockery::mock(ResponseInterface::class);
        $responseMock->shouldReceive('getBody')->andReturn($body);
        return $responseMock;
      });

    $this->router = new Router($scannerMock, $argumentsResolverServiceMock, $converterManagerMock);
  }

  /**
   * Get Request mock object.
   *
   * @param string $path
   *   Request path.
   *
   * @return \Psr\Http\Message\RequestInterface
   */
  private function getRequestMock($path): RequestInterface {
    $requestMock = Mockery::mock(ServerRequestInterface::class);
    $requestMock->shouldReceive('getUri->getPath')->andReturn($path);
    return $requestMock;
  }

  /**
   * @covers \ARouter\Routing\Router
   */
  public function testGetResponseNormalFlow() {
    $request = $this->getRequestMock('simple-action');
    $response = $this->router->getResponse($request);
    $this->assertEquals($response->getBody(), "This is a simple action");
  }

  /**
   * @covers \ARouter\Routing\Router
   * @covers \ARouter\Routing\Exception\RouteHandlerNotFoundException
   */
  public function testGetResponseNotFoundException() {
    $requestMock = $this->getRequestMock('non-existing-path');
    $requestMock->shouldReceive('getMethod')->andReturn('GET');
    try {
      $this->router->getResponse($requestMock);
      $this->fail();
    } catch (RouteHandlerNotFoundException $ex) {
      $this->assertEquals($ex->getRequest(), $requestMock);
    }
  }

  /**
   * @covers \ARouter\Routing\Router
   */
  public function testGetResponseArgumentsResolverFlow() {
    $request = $this->getRequestMock('parameter-in-path/nameValue');
    $response = $this->router->getResponse($request);
    $this->assertEquals($response->getBody(), "Given parameter is nameValue");
  }

  /**
   * @covers \ARouter\Routing\Router
   */
  public function testGetResponseConverterFlow() {
    $request = $this->getRequestMock('converter-action');
    $response = $this->router->getResponse($request);
    $this->assertEquals($response->getBody(), "string response");
  }

}
