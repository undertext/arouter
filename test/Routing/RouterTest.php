<?php

namespace ARouter\Routing;

use ARouter\Routing\Converter\JsonHttpMessageConverter;
use ARouter\Routing\HttpMessageConverter\HttpMessageConverterManager;
use ARouter\Routing\Scanner\RouteMappingsScannerInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;
use ARouter\Routing\Controllers\SubDirectory\TestController2;
use ARouter\Routing\Controllers\TestController;
use ARouter\Routing\Resolver\MethodArgumentResolver;
use ARouter\Routing\Resolver\PathArgumentResolver;
use ARouter\Routing\Resolver\RequestArgumentResolver;
use ARouter\Routing\Resolver\RequestBodyArgumentResolver;
use ARouter\Routing\Resolver\RequestParamArgumentResolver;

/**
 * Tests Router functionality.
 */
class RouterTest extends TestCase {

  /**
   * Route mappings for test.
   *
   * @var RouteMapping[]
   */
  public $routeMappings;

  /**
   * Request mock object for test.
   *
   * @var ServerRequestInterface
   */
  public $request;

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    $testRouteMapping1 = $this->createMock(RouteMapping::class);
    $testRouteMapping1->method('getPath')->willReturn('path1/{name}');
    $testRouteMapping1->method('getController')
      ->willReturn(TestController::class);
    $testRouteMapping1->method('getMethod')->willReturn('action');

    $testRouteMapping2 = $this->createMock(RouteMapping::class);
    $testRouteMapping2->method('getPath')->willReturn('path2');
    $this->routeMappings = [$testRouteMapping1, $testRouteMapping2];

    $this->request = $this->createMock(ServerRequestInterface::class);
  }

  /**
   * Tests route handler mismatch.
   */
  public function testGetNullRouteHandler() {
    $router = new Router();
    $router->addRouteMappings($this->routeMappings);

    $uri = $this->createMock(UriInterface::class);
    $uri->method('getPath')->willReturn('test-non-matched-path');
    $this->request->method('getUri')->willReturn($uri);

    $routeHandler = $router->getRouteHandler($this->request);
    self::assertNull($routeHandler);
  }

  /**
   * Tests route handler match.
   */
  public function testGetRouteHandler() {
    $argumentResolver = $this->createMock(MethodArgumentResolver::class);
    $argumentResolver->method('resolve')->willReturn([
      'name' => 'testname',
      'arg1' => 'testarg1'
    ]);

    $router = new Router();
    $router->addRouteMappings($this->routeMappings);
    $router->addArgumentResolvers([$argumentResolver]);
    $uri = $this->createMock(UriInterface::class);
    $uri->method('getPath')->willReturn('path1/testname');
    $this->request->method('getUri')->willReturn($uri);
    $routeHandler = $router->getRouteHandler($this->request);
    $controller = $routeHandler->getController();

    self::assertEquals(get_class($controller), TestController::class);
    self::assertEquals($routeHandler->getKeyedArgs(), [
      'name' => 'testname',
      'arg1' => 'testarg1'
    ]);
    self::assertEquals('action', $routeHandler->getMethod());
  }

  /**
   * Tests route handler mismatch.
   */
  public function testConvertor() {
    $testObject = new class {

      public $first = 'a';

      public $second = 'b';

      /**
       * @return string
       */
      public function getFirst(): string {
        return $this->first;
      }

      /**
       * @param string $first
       */
      public function setFirst(string $first): void {
        $this->first = $first;
      }

      /**
       * @return string
       */
      public function getSecond(): string {
        return $this->second;
      }

      /**
       * @param string $second
       */
      public function setSecond(string $second): void {
        $this->second = $second;
      }
    };


    $routeHandler = $this->createMock(RouteHandler::class);
    $routeHandler->method('execute')->willReturn($testObject);

    $router = $this->createPartialMock(Router::class,['getRouteHandler']);
    $router->setConverterManager(new HttpMessageConverterManager());
    $router->getConverterManager()->addConverter(new JsonHttpMessageConverter());


    $router->method('getRouteHandler')->willReturn($routeHandler);


    $uri = $this->createMock(UriInterface::class);
    $uri->method('getPath')->willReturn('testpath3');
    $this->request->method('getUri')->willReturn($uri);

    $response = $router->getResponse($this->request);
    self::assertEquals((string) $response->getBody(), '{"first":"a","second":"b"}');
  }


  /**
   * Tests creation of default router.
   */
  public function testFromDirectory() {
    $router = Router::build('test/Routing/Controllers');
    $routeMappings = $router->getRouteMappings();
    self::assertEquals($routeMappings[0]->getPath(), 'testpath2');
    self::assertEquals($routeMappings[1]->getPath(), 'testpath');

    self::assertEquals($routeMappings[0]->getController(), TestController2::class);
    self::assertEquals($routeMappings[1]->getController(), TestController::class);

    self::assertEquals($routeMappings[0]->getMethod(), 'action2');
    self::assertEquals($routeMappings[1]->getMethod(), 'action');

    $resolvers = $router->getArgumentsResolvers();

    self::assertInstanceOf(RequestArgumentResolver::class, $resolvers[0]);
    self::assertInstanceOf(RequestParamArgumentResolver::class, $resolvers[1]);
    self::assertInstanceOf(PathArgumentResolver::class, $resolvers[2]);
    self::assertInstanceOf(RequestBodyArgumentResolver::class, $resolvers[3]);

    $scanner = self::createMock(RouteMappingsScannerInterface::class);
    $router->setScanner($scanner);
    self::assertEquals($router->getScanner(), $scanner);
  }

}
