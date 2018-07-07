<?php

namespace ARouter\Routing;

use ARouter\Routing\Exception\ApplicableConvertorNotFoundException;
use ARouter\Routing\Exception\RouteHandlerNotFoundException;
use ARouter\Routing\HttpMessageConverter\HttpMessageConverterManager;
use ARouter\Routing\Scanner\AnnotationRouteMappingsScanner;
use ARouter\Routing\Scanner\RouteMappingsScannerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use ARouter\Routing\Resolver\{
  CookieValueArgumentResolver,
  RequestArgumentResolver,
  PathArgumentResolver,
  RequestBodyArgumentResolver,
  RequestHeaderArgumentResolver,
  RequestParamArgumentResolver,
  SessionAttributeArgumentResolver
};

/**
 * Main class for interaction with routing system.
 *
 * This router is PSR-7 compatible, this means that it works with
 * <strong>ServerRequestInterface</strong>.
 *
 * Here is a basic example of using the Router class.
 * ```php
 * // Create router and pick up routes from 'src/Controller' directory.
 * $router = Router::build('src/Controller');
 * // Get response for given request.
 * $response = $router->getResponse($request);
 * ```
 */
class Router {

  /**
   * Route mappings scanner.
   *
   * @var \ARouter\Routing\Scanner\RouteMappingsScannerInterface
   */
  private $scanner;

  /**
   * Discovered route mappings.
   *
   * @var \ARouter\Routing\RouteMapping[]
   */
  private $routeMappings = [];

  /**
   * Action method arguments resolver.
   *
   * @var \ARouter\Routing\Resolver\MethodArgumentResolver[]
   */
  private $argumentsResolvers = [];

  /**
   * HTTP message converter manager.
   *
   * @var \ARouter\Routing\HttpMessageConverter\HttpMessageConverterManager
   */
  private $converterManager;


  /**
   * Build annotation based router.
   *
   * Created router has all provided by this library argument resolvers,
   * and use provided by this library Route mappings scanner.
   *
   * @param string $controllersDirectory
   *   Directory where controllers are stored.
   *
   * @return \ARouter\Routing\Router
   *   Annotation based router.
   */
  public static function build(string $controllersDirectory): Router {
    $router = new static();
    $router->addArgumentResolvers([
      new RequestArgumentResolver(),
      new RequestParamArgumentResolver(),
      new PathArgumentResolver(),
      new RequestBodyArgumentResolver(),
      new CookieValueArgumentResolver(),
      new RequestHeaderArgumentResolver(),
      new SessionAttributeArgumentResolver(),
    ]);
    $router->converterManager = new HttpMessageConverterManager();
    $router->scanner = new AnnotationRouteMappingsScanner($controllersDirectory);
    $router->discoverRouteMappings();
    return $router;
  }

  /**
   * Discover mappings between routes and controller action methods.
   */
  public function discoverRouteMappings(): void {
    $this->routeMappings = array_merge($this->routeMappings, $this->scanner->discoverRouteMappings());
  }

  /**
   * Get route handler for given request.
   *
   * @param \Psr\Http\Message\ServerRequestInterface $request
   *   HTTP request.
   *
   * @return null|\ARouter\Routing\RouteHandler
   *   Route handler for given request or NULL if there is no matching handler.
   */
  public function getRouteHandler(ServerRequestInterface $request) {
    $requestPath = $request->getUri()->getPath();
    foreach ($this->routeMappings as $routeMapping) {
      $quotedRoutePath = preg_quote($routeMapping->getPath(), '/');
      $quotedRoutePathRegex = '/^' . preg_replace('/\\\\{(.*)\\\\}/', '(?<$1>.*)', $quotedRoutePath) . '$/';
      if (preg_match($quotedRoutePathRegex, $requestPath, $matches) !== 0) {
        $controllerName = $routeMapping->getController();
        $routeHandler = new RouteHandler($this->getControllerInstance($controllerName), $routeMapping->getMethod(), []);
        $method = new \ReflectionMethod($routeMapping->getController(), $routeMapping->getMethod());
        $methodParams = $method->getParameters();
        $keyedMethodParams = [];
        foreach ($methodParams as $methodParam) {
          $keyedMethodParams[$methodParam->name] = $methodParam;
        }
        foreach ($this->argumentsResolvers as $argumentResolver) {
          $resolvedArguments = $argumentResolver->resolve($keyedMethodParams, $routeMapping, $request);
          $routeHandler->addArguments($resolvedArguments);
        }
        return $routeHandler;
      }
    }
    return NULL;
  }

  /**
   * Get HTTP response object for given request.
   *
   * @param \Psr\Http\Message\ServerRequestInterface $request
   *   HTTP request.
   *
   * @return \Psr\Http\Message\ResponseInterface
   *   HTTP response object for given request.
   * @throws \Exception
   */
  public function getResponse(ServerRequestInterface $request): ResponseInterface {
    $routeHandler = $this->getRouteHandler($request);
    if (empty($routeHandler)) {
      throw new RouteHandlerNotFoundException();
    }
    $result = $routeHandler->execute();
    if (!$result instanceof ResponseInterface) {
      $convertor = $this->converterManager->getApplicableConverter($request);
      if (empty($convertor)) {
        throw new ApplicableConvertorNotFoundException();
      }
      $result = $convertor->toResponse($result);
    }
    return $result;
  }

  /**
   * Get controller instance.
   *
   * @param string $controllerName
   *   Full class name of controller class.
   *
   * @return object
   *   Controller instance.
   */
  protected function getControllerInstance(string $controllerName) {
    return new $controllerName;
  }

  /**
   * Add route mappings to router.
   *
   * @param \ARouter\Routing\RouteMapping[] $routeMappings
   *   Route mappings for adding.
   */
  public function addRouteMappings(array $routeMappings): void {
    $this->routeMappings = array_merge($this->routeMappings, $routeMappings);
  }

  /**
   * Get route mappings from router.
   *
   * @return \ARouter\Routing\RouteMapping[]
   *   Route mappings from router.
   */

  public function getRouteMappings(): array {
    return $this->routeMappings;
  }

  /**
   * Add arguments resolvers to router.
   *
   * @param \ARouter\Routing\Resolver\MethodArgumentResolver[] $argumentResolvers
   *   Arguments resolvers.
   */
  public function addArgumentResolvers(array $argumentResolvers) {
    $this->argumentsResolvers = array_merge($this->argumentsResolvers, $argumentResolvers);
  }

  /**
   * Get arguments resolvers from router.
   *
   * @return \ARouter\Routing\Resolver\MethodArgumentResolver[]
   *   Arguments resolvers from router.
   */
  public function getArgumentsResolvers(): array {
    return $this->argumentsResolvers;
  }

  /**
   * Set route mappings scanner for router.
   *
   * @param \ARouter\Routing\Scanner\RouteMappingsScannerInterface $scanner
   *   Route mappings scanner for router.
   */
  public function setScanner(RouteMappingsScannerInterface $scanner): void {
    $this->scanner = $scanner;
  }

  /**
   * Get route mappings scanner from router.
   *
   * @return \ARouter\Routing\Scanner\RouteMappingsScannerInterface
   *   Route mappings scanner from router.
   */
  public function getScanner(): RouteMappingsScannerInterface {
    return $this->scanner;
  }

  /**
   * Get HTTP message convertor manager.
   *
   * @return \ARouter\Routing\HttpMessageConverter\HttpMessageConverterManager
   */
  public function getConverterManager(): HttpMessageConverterManager {
    return $this->converterManager;
  }

  /**
   * Set HTTP message convertor manager.
   *
   * @param \ARouter\Routing\HttpMessageConverter\HttpMessageConverterManager $converterManager
   *   HTTP message convertor manager.
   */
  public function setConverterManager(HttpMessageConverterManager $converterManager): void {
    $this->converterManager = $converterManager;
  }


}
