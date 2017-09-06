<?php

namespace ARouter\Routing\Resolver;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ServerRequestInterface;
use ARouter\Routing\RouteMapping;

/**
 * Resolve Request object arguments.
 *
 * Action method argument of 'RequestInterface' type like
 * ```php
 * public function hello(RequestInterface $r){}
 * ```
 * will be resolved to incoming HTTP Request object.
 */
class RequestArgumentResolver implements MethodArgumentResolver {

  /**
   * {@inheritdoc}
   */
  public function resolve(array $methodParams, RouteMapping $routeMapping, ServerRequestInterface $request): array {
    $args = [];
    foreach ($methodParams as $methodParam) {
      /** @var \ReflectionParameter $methodParam */
      if ($methodParam->getClass() && $methodParam->getClass()->implementsInterface(RequestInterface::class)) {
        $args[$methodParam->getName()] = $request;
      }
    }
    return $args;
  }
}
