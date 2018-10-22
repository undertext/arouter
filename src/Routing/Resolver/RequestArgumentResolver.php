<?php

namespace ARouter\Routing\Resolver;

use ARouter\Routing\RouteMatch;
use Psr\Http\Message\RequestInterface;

/**
 * @addtogroup argument_resolvers
 *
 * ## Request object resolver
 * Method parameter of 'RequestInterface' type like
 * ```php
 * public function hello(RequestInterface $r){}
 * ```
 * will be resolved to incoming HTTP Request object.
 */

/**
 * Resolves Request object.
 */
class RequestArgumentResolver implements MethodArgumentResolver {

  /**
   * {@inheritdoc}
   */
  public function resolve(array $methodParams, RouteMatch $routeMatch): array {
    $args = [];
    foreach ($methodParams as $methodParam) {
      /** @var \ReflectionParameter $methodParam */
      if ($methodParam->getClass() && $methodParam->getClass()
          ->implementsInterface(RequestInterface::class)) {
        $args[$methodParam->getName()] = $routeMatch->getRequest();
      }
    }
    return $args;
  }
}
