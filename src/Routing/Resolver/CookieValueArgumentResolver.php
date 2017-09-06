<?php

namespace ARouter\Routing\Resolver;

use Psr\Http\Message\ServerRequestInterface;
use ARouter\Routing\Annotation\CookieValue;
use ARouter\Routing\RouteMapping;

/**
 *  Resolve request body object arguments.
 *
 * @see \ARouter\Routing\Annotation\CookieValue
 */
class CookieValueArgumentResolver implements MethodArgumentResolver {

  /**
   * {@inheritdoc}
   */
  public function resolve(array $methodParams, RouteMapping $routeMapping, ServerRequestInterface $request): array {
    $args = [];
    $cookies = $request->getCookieParams();;
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
