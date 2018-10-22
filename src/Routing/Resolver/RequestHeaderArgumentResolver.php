<?php

namespace ARouter\Routing\Resolver;

use ARouter\Routing\RouteMatch;
use ARouter\Routing\Annotation\RequestHeader;

/**
 *  Resolve request header object arguments.
 *
 * @see \ARouter\Routing\Annotation\RequestHeader
 */
class RequestHeaderArgumentResolver implements MethodArgumentResolver {

  /**
   * {@inheritdoc}
   */
  public function resolve(array $methodParams, RouteMatch $routeMatch): array {
    $args = [];
    $routeMapping = $routeMatch->getRouteMapping();
    foreach ($routeMapping->getAnnotations() as $annotation) {
      if ($annotation instanceof RequestHeader) {
        $queryParamName = $annotation->for;
        if (isset($methodParams[$queryParamName])) {
          $args[$queryParamName] = $routeMatch->getRequest()->getHeader($annotation->from);
        }
      }
    }
    return $args;
  }
}
