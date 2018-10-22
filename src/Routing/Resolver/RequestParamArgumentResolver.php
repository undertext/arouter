<?php

namespace ARouter\Routing\Resolver;

use ARouter\Routing\RouteMatch;
use Psr\Http\Message\UploadedFileInterface;
use ARouter\Routing\Annotation\RequestParam;

/**
 * Request parameter argument resolver.
 *
 * @see \ARouter\Routing\Annotation\RequestParam
 */
class RequestParamArgumentResolver implements MethodArgumentResolver {

  /**
   * {@inheritdoc}
   */
  public function resolve(array $methodParams, RouteMatch $routeMatch): array {
    $args = [];
    $routeMapping = $routeMatch->getRouteMapping();
    $request = $routeMatch->getRequest();
    $queryParams = $request->getQueryParams();
    foreach ($routeMapping->getAnnotations() as $annotation) {
      if ($annotation instanceof RequestParam) {
        $queryParamName = $annotation->from;
        $methodArgumentName = $annotation->for;
        if (!empty($queryParams[$queryParamName]) || !empty($request->getUploadedFiles()[$queryParamName])) {
          if (isset($methodParams[$queryParamName])) {
            if ($methodParams[$queryParamName]->hasType()) {
              $type = (string) ($methodParams[$queryParamName]->getType());
              if ($type == UploadedFileInterface::class) {
                $args[$methodArgumentName] = $request->getUploadedFiles()[$queryParamName];
              }
            }
            else {
              $args[$methodArgumentName] = $queryParams[$queryParamName];
            }
          }
        }
      }
    }
    return $args;
  }
}
