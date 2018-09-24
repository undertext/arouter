<?php

namespace ARouter\Routing;

use ARouter\Routing\Exception\RouteHandlerNotFoundException;
use ARouter\Routing\Factory\RouterFactory;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Mockery;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Tests Router functionality.
 *
 * @coversNothing
 */
class RouterFunctionalTest extends TestCase {

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
    AnnotationRegistry::registerLoader('class_exists');
    $this->router = (new RouterFactory())->getRouter(__DIR__ . '/Controllers');
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
   * Tests Router normal flow.
   */
  public function testGetResponseNormalFlow() {
    $request = $this->getRequestMock('simple-action');
    $request->shouldReceive('getMethod')->andReturn('GET');
    $request->shouldReceive('getQueryParams')->andReturn([]);
    $request->shouldReceive('getCookieParams')->andReturn([]);
    $response = $this->router->getResponse($request);
    $this->assertEquals($response->getBody(), "This is a simple action");
  }

  /**
   * Tests non-existing route.
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
   * Tests arguments resolvers.
   */
  public function testGetResponseArgumentsResolverFlow() {
    $request = $this->getRequestMock('parameter-in-path/value');
    $request->shouldReceive('getQueryParams')->andReturn([]);
    $request->shouldReceive('getCookieParams')->andReturn([]);
    $request->shouldReceive('getMethod')->andReturn('GET');
    $response = $this->router->getResponse($request);
    $this->assertEquals($response->getBody(), "Given parameter is value");
  }

}
