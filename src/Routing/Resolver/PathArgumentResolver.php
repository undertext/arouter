<?php

namespace ARouter\Routing\Resolver;

use Psr\Http\Message\ServerRequestInterface;
use ARouter\Routing\RouteMapping;

/**
 * Resolve request path arguments.
 *
 * If action method has placeholders in @Route path like
 * ```php
 * @Route (path="/user/{name}")
 * public function profile($name){}
 * ```
 * then arguments named as those placeholders will be resolved to placeholder
 * values.
 */
class PathArgumentResolver implements MethodArgumentResolver {

  /**
   * {@inheritdoc}
   */
  public function resolve(array $methodParams, RouteMapping $routeMapping, ServerRequestInterface $request): array {
    $quotedRoutePath = preg_quote($routeMapping->getPath(), '/');
    $routeParamsRegex = '/' . preg_replace('/\\\\{(.*)\\\\}/', '(?<$1>.*)', $quotedRoutePath) . '/';
    preg_match($routeParamsRegex, $request->getUri()->getPath(), $matches);
    $fields = array_filter(array_keys($matches), "is_string");
    $args = [];
    foreach ($fields as $field) {
      $args[$field] = $matches[$field];
    }
    return $args;
  }
}
