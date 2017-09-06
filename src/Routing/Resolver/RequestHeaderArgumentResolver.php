<?php

namespace ARouter\Routing\Resolver;

use Psr\Http\Message\ServerRequestInterface;
use ARouter\Routing\Annotation\RequestHeader;
use ARouter\Routing\RouteMapping;

/**
 *  Resolve request header object arguments.
 *
 * @see \ARouter\Routing\Annotation\RequestHeader
 */
class RequestHeaderArgumentResolver implements MethodArgumentResolver {

  /**
   * {@inheritdoc}
   */
  public function resolve(array $methodParams, RouteMapping $routeMapping, ServerRequestInterface $request): array {
    $args = [];
    foreach ($routeMapping->getAnnotations() as $annotation) {
      if ($annotation instanceof RequestHeader) {
        $queryParamName = $annotation->for;
        if (isset($methodParams[$queryParamName])) {
          $args[$queryParamName] = $request->getHeader($annotation->from);
        }
      }
    }
    return $args;
  }
}
