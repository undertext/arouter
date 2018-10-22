<?php

namespace ARouter\Routing\Resolver;

use ARouter\Routing\RouteMatch;

/**
 * @addtogroup routes_declaring
 *
 * ## Path arguments
 *
 * If @Route annotation contains placeholders in "path" like
 * ```php
 * @Route (path="/user/{name}")
 * public function profile($name){}
 * ```
 * then arguments named as those placeholders will be resolved to placeholder
 * values.
 */

/**
 * Resolve request path arguments.
 */
class PathArgumentResolver implements MethodArgumentResolver {

  /**
   * {@inheritdoc}
   */
  public function resolve(array $methodParams, RouteMatch $routeMatch): array {
    $pathArguments = $routeMatch->getPathArguments();
    $fields = array_filter(array_keys($pathArguments), "is_string");
    $args = [];
    foreach ($fields as $field) {
      $args[$field] = $pathArguments[$field];
    }
    return $args;
  }
}
