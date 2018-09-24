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
 * $router = RouterFactory::getRouter('src/Controller');
 * // Get response for given request.
 * $response = $router->getResponse($request);
 * ```
 */
class Router implements RouterInterface {

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
  protected $routeMappings = NULL;

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
   * Router constructor.
   *
   * @param \ARouter\Routing\Scanner\RouteMappingsScannerInterface $scanner
   * @param \ARouter\Routing\Resolver\Service\MethodArgumentsResolverService $argumentsResolverService
   * @param \ARouter\Routing\HttpMessageConverter\HttpMessageConverterManager $converterManager
   */
  public function __construct(RouteMappingsScannerInterface $scanner, MethodArgumentsResolverService $argumentsResolverService, HttpMessageConverterManager $converterManager) {
    $this->scanner = $scanner;
    $this->argumentsResolverService = $argumentsResolverService;
    $this->converterManager = $converterManager;
  }


  /**
   * {@inheritdoc}
   *
   * @throws \ARouter\Routing\Exception\ApplicableConverterNotFoundException
   *   When applicable converter is not found.
   * @throws \ARouter\Routing\Exception\RouteHandlerNotFoundException
   *   When route handler is not found.
   */
  public function getResponse(ServerRequestInterface $request): ResponseInterface {
    if ($this->routeMappings == NULL) {
      $this->routeMappings = $this->scanner->discoverRouteMappings();
    }
    $routeHandler = $this->getRouteHandler($request);
    if (empty($routeHandler)) {
      throw new RouteHandlerNotFoundException($request);
    }
    $result = $routeHandler->execute();
    if (!$result instanceof ResponseInterface) {
      $result = $this->converterManager->convertToResponse($result, $request);
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
   * Get route handler for given request.
   *
   * @param \Psr\Http\Message\ServerRequestInterface $request
   *   HTTP request.
   *
   * @return null|\ARouter\Routing\RouteHandler
   *   Route handler for given request or NULL if there is no matching handler.
   *
   * @throws \ReflectionException
   */
  private function getRouteHandler(ServerRequestInterface $request) {
    $matchingRouteMapping = $this->getMatchingRouteMapping($request);
    if (!empty($matchingRouteMapping)) {
      $controllerName = $matchingRouteMapping->getController();
      $method = new \ReflectionMethod($matchingRouteMapping->getController(), $matchingRouteMapping->getMethod());
      $resolvedArguments = $this->argumentsResolverService->resolveArguments($method->getParameters(), $matchingRouteMapping, $request);
      $routeHandler = new RouteHandler($this->getControllerInstance($controllerName), $matchingRouteMapping->getMethod(), $resolvedArguments);
      return $routeHandler;
    }
    return NULL;
  }

  /**
   * Get matching RouteMapping for incoming request.
   *
   * @param \Psr\Http\Message\ServerRequestInterface $request
   *   Incoming request
   *
   * @return \ARouter\Routing\RouteMapping|null
   *   Matching RouteMapping or NULL.
   */
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

}
