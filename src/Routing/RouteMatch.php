<?php

namespace ARouter\Routing;

use Psr\Http\Message\ServerRequestInterface;

/**
 * Route match.
 *
 * Represents a match between route mapping and incoming request.
 */
class RouteMatch {

  /**
   * Matching route mapping.
   *
   * @var \ARouter\Routing\RouteMapping
   */
  private $routeMapping;

  /**
   * Request object.
   *
   * @var \Psr\Http\Message\ServerRequestInterface
   */
  private $request;

  /**
   * Matched path arguments.
   *
   * @var array
   */
  private $pathArguments;

  /**
   * RouteMatch constructor.
   *
   * @param \ARouter\Routing\RouteMapping $routeMapping
   *   Route mapping.
   * @param \Psr\Http\Message\ServerRequestInterface $request
   *   Request object.
   * @param array $pathArguments
   *   Matched path arguments.
   */
  public function __construct(RouteMapping $routeMapping, ServerRequestInterface $request, array $pathArguments) {
    $this->routeMapping = $routeMapping;
    $this->request = $request;
    $this->pathArguments = $pathArguments;
  }

  /**
   * Get route mapping.
   *
   * @return \ARouter\Routing\RouteMapping
   *   Route mapping.
   */
  public function getRouteMapping(): RouteMapping {
    return $this->routeMapping;
  }

  /**
   * Get request object.
   *
   * @return \Psr\Http\Message\ServerRequestInterface
   *   Request object.
   */
  public function getRequest(): ServerRequestInterface {
    return $this->request;
  }

  /**
   * Get matched path arguments.
   *
   * @return array
   *   Matched path arguments.
   */
  public function getPathArguments(): array {
    return $this->pathArguments;
  }

}
