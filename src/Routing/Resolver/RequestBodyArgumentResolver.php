<?php

namespace ARouter\Routing\Resolver;

use ARouter\Routing\RouteMatch;
use ARouter\Routing\Annotation\RequestBody;

/**
 *  Resolve request body object arguments.
 *
 * @see \ARouter\Routing\Annotation\RequestBody
 */
class RequestBodyArgumentResolver implements MethodArgumentResolver {

  /**
   * {@inheritdoc}
   */
  public function resolve(array $methodParams, RouteMatch $routeMatch): array {
    $args = [];
    $routeMapping = $routeMatch->getRouteMapping();
    foreach ($routeMapping->getAnnotations() as $annotation) {
      if ($annotation instanceof RequestBody) {
        $queryParamName = $annotation->for;
        if (isset($methodParams[$queryParamName])) {
          $args[$queryParamName] = $routeMatch->getRequest()->getBody()->__toString();
        }
      }
    }
    return $args;
  }
}
