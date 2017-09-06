<?php

namespace ARouter\Routing\Resolver;

use Psr\Http\Message\ServerRequestInterface;
use ARouter\Routing\Annotation\SessionAttribute;
use ARouter\Routing\RouteMapping;

/**
 *  Resolve request body object arguments.
 *
 * @see \ARouter\Routing\Annotation\SessionAttribute
 */
class SessionAttributeArgumentResolver implements MethodArgumentResolver {

  /**
   * {@inheritdoc}
   */
  public function resolve(array $methodParams, RouteMapping $routeMapping, ServerRequestInterface $request): array {
    $args = [];
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
