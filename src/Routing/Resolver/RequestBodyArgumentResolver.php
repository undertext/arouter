<?php

namespace ARouter\Routing\Resolver;

use Psr\Http\Message\ServerRequestInterface;
use ARouter\Routing\Annotation\RequestBody;
use ARouter\Routing\RouteMapping;

/**
 *  Resolve request body object arguments.
 *
 * @see \ARouter\Routing\Annotation\RequestBody
 */
class RequestBodyArgumentResolver implements MethodArgumentResolver {

  /**
   * {@inheritdoc}
   */
  public function resolve(array $methodParams, RouteMapping $routeMapping, ServerRequestInterface $request): array {
    $args = [];
    foreach ($routeMapping->getAnnotations() as $annotation) {
      if ($annotation instanceof RequestBody) {
        $queryParamName = $annotation->for;
        if (isset($methodParams[$queryParamName])) {
          $args[$queryParamName] = $request->getBody()->__toString();
        }
      }
    }
    return $args;
  }
}
