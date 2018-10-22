<?php

namespace ARouter\Routing\Resolver;

use ARouter\Routing\RouteMatch;

/**
 * @defgroup argument_resolvers Argument resolvers
 *
 * Argument resolvers are designed to map data from incoming Request to
 * controller method parameters using annotations.
 */

/**
 * Interface for classes that want to resolve method arguments
 * based on incoming HTTP request.
 */
interface MethodArgumentResolver {

  /**
   * Resolve method arguments.
   *
   * @param \ReflectionParameter[] $methodParams
   *   Method parameters.
   * @param \ARouter\Routing\RouteMatch $routeMatch
   *   Route match.
   *
   * @return array
   *   Resolved method arguments.
   */
  public function resolve(array $methodParams, RouteMatch $routeMatch): array;

}
