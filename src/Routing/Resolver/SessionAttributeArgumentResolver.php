<?php

namespace ARouter\Routing\Resolver;

use ARouter\Routing\RouteMatch;
use ARouter\Routing\Annotation\SessionAttribute;

/**
 *  Resolve request body object arguments.
 *
 * @see \ARouter\Routing\Annotation\SessionAttribute
 */
class SessionAttributeArgumentResolver implements MethodArgumentResolver {

  /**
   * {@inheritdoc}
   */
  public function resolve(array $methodParams, RouteMatch $routeMatch): array {
    $args = [];
    $routeMapping = $routeMatch->getRouteMapping();
    foreach ($routeMapping->getAnnotations() as $annotation) {
      if ($annotation instanceof SessionAttribute) {
        $queryParamName = $annotation->for;
        if (isset($methodParams[$queryParamName]) && isset($_SESSION[$annotation->from])) {
          $args[$queryParamName] = $_SESSION[$annotation->from];
        }
      }
    }
    return $args;
  }
}
