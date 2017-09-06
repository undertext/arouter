<?php

namespace ARouter\Routing\Resolver;

use Psr\Http\Message\ServerRequestInterface;
use ARouter\Routing\RouteMapping;

/**
 * Interface for classes that want to resolve action method arguments
 * based on incoming HTTP request.
 */
interface MethodArgumentResolver {

  /**
   * Resolve method arguments.
   *
   * @param \ReflectionParameter[] $methodParams
   *   Method parameters.
   * @param \ARouter\Routing\RouteMapping $routeMapping
   *   Route mapping.
   * @param \Psr\Http\Message\ServerRequestInterface $request
   *   Incoming HTTP request.
   *
   * @return array
   *   Resolved method arguments.
   */
  public function resolve(array $methodParams, RouteMapping $routeMapping, ServerRequestInterface $request): array;

}
