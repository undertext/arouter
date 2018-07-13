<?php

namespace ARouter\Routing;

use ARouter\Routing\Exception\ApplicableConverterNotFoundException;
use ARouter\Routing\Exception\RouteHandlerNotFoundException;
use ARouter\Routing\HttpMessageConverter\HttpMessageConverterManager;
use ARouter\Routing\Scanner\AnnotationRouteMappingsScanner;
use ARouter\Routing\Scanner\RouteMappingsScannerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use ARouter\Routing\Resolver\{
  CookieValueArgumentResolver, Service\MethodArgumentsResolverService, RequestArgumentResolver, PathArgumentResolver, RequestBodyArgumentResolver, RequestHeaderArgumentResolver, RequestParamArgumentResolver, SessionAttributeArgumentResolver
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
  protected $routeMappings = [];

  /**
   * Method arguments resolver manager.
   *
   * @var \ARouter\Routing\Resolver\Service\MethodArgumentsResolverService
   */
  private $argumentsResolverService;


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
  public static function build(string $controllersDirectory, ...$options): Router {
    $router = new static(...$options);
    $router->argumentsResolverService = new MethodArgumentsResolverService();
    $router->argumentsResolverService->addArgumentResolvers([
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
    $this->routeMappings = $this->scanner->discoverRouteMappings();
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
    $matchingRouteMapping = $this->getMatchingRouteMapping($request);
    if (!empty($matchingRouteMapping)) {
      $controllerName = $matchingRouteMapping->getController();
      $routeHandler = new RouteHandler($this->getControllerInstance($controllerName), $matchingRouteMapping->getMethod(), []);
      $method = new \ReflectionMethod($matchingRouteMapping->getController(), $matchingRouteMapping->getMethod());
      $resolvedArguments = $this->argumentsResolverService->resolveArguments($method->getParameters(), $matchingRouteMapping, $request);
      $routeHandler->addArguments($resolvedArguments);
      return $routeHandler;
    }
    return NULL;
  }

  private function getMatchingRouteMapping(ServerRequestInterface $request) {
    $requestPath = $request->getUri()->getPath();
    foreach ($this->routeMappings as $routeMapping) {
      $quotedRoutePath = preg_quote($routeMapping->getPath(), '/');
      $quotedRoutePathRegex = '/^' . preg_replace('/\\\\{(.*)\\\\}/', '(?<$1>.*)', $quotedRoutePath) . '$/';
      if (preg_match($quotedRoutePathRegex, $requestPath, $matches) !== 0) {
        return $routeMapping;
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
   *
   * @throws \ARouter\Routing\Exception\ApplicableConverterNotFoundException
   *   When applicable converter is not found.
   * @throws \ARouter\Routing\Exception\RouteHandlerNotFoundException
   *   When route handler is not found.
   */
  public function getResponse(ServerRequestInterface $request): ResponseInterface {
    $routeHandler = $this->getRouteHandler($request);
    if (empty($routeHandler)) {
      throw new RouteHandlerNotFoundException($request);
    }
    $result = $routeHandler->execute();
    if (!$result instanceof ResponseInterface) {
      $converter = $this->converterManager->getApplicableConverter($request);
      if (empty($converter)) {
        throw new ApplicableConverterNotFoundException($result, $this->converterManager->getConverters());
      }
      $result = $converter->toResponse($result);
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


  public function setArgumentsResolverService($argumentsResolverService) {
    $this->argumentsResolverService = $argumentsResolverService;
  }

  /**
   * @return \ARouter\Routing\Resolver\Service\MethodArgumentsResolverService
   */
  public function getArgumentsResolverService(): MethodArgumentsResolverService {
    return $this->argumentsResolverService;
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
   * Get HTTP message converter manager.
   *
   * @return \ARouter\Routing\HttpMessageConverter\HttpMessageConverterManager
   */
  public function getConverterManager(): HttpMessageConverterManager {
    return $this->converterManager;
  }

  /**
   * Set HTTP message converter manager.
   *
   * @param \ARouter\Routing\HttpMessageConverter\HttpMessageConverterManager $converterManager
   *   HTTP message converter manager.
   */
  public function setConverterManager(HttpMessageConverterManager $converterManager): void {
    $this->converterManager = $converterManager;
  }


}
