<?php

namespace ARouter\Routing\Resolver;

use ARouter\Routing\RouteMatch;
use ARouter\Routing\Annotation\CookieValue;

/**
 *  Resolve request body object arguments.
 *
 * @see \ARouter\Routing\Annotation\CookieValue
 */
class CookieValueArgumentResolver implements MethodArgumentResolver {

  /**
   * {@inheritdoc}
   */
  public function resolve(array $methodParams, RouteMatch $routeMatch): array {
    $args = [];
    $cookies = $routeMatch->getRequest()->getCookieParams();
    $routeMapping = $routeMatch->getRouteMapping();
    foreach ($routeMapping->getAnnotations() as $annotation) {
      if ($annotation instanceof CookieValue) {
        $queryParamName = $annotation->for;
        if (isset($methodParams[$queryParamName]) && isset($cookies[$annotation->from])) {
          $args[$queryParamName] = $cookies[$annotation->from];
        }
      }
    }
    return $args;
  }
}
