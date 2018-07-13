<?php

namespace ARouter\Routing\Resolver\Service;

class MethodArgumentsResolverService {

  private $argumentResolvers = [];

  /**
   * @param $controller
   * @param \ReflectionProperty[] $methodParams
   *
   * @return mixed
   * @throws \ReflectionException
   */
  public function resolveArguments($methodParams, $routeMapping, $request) {
    $resolvedArguments = [];
    $keyedMethodParams = [];
    foreach ($methodParams as $methodParam) {
      $keyedMethodParams[$methodParam->getName()] = $methodParam;
    }
    foreach ($this->argumentResolvers as $argumentResolver) {
      $resolvedArguments = array_merge($resolvedArguments, $argumentResolver->resolve($keyedMethodParams, $routeMapping, $request));
    }
    return $resolvedArguments;
  }

  /**
   * @return array
   */
  public function getArgumentResolvers(): array {
    return $this->argumentResolvers;
  }

  /**
   * @param array $argumentResolvers
   */
  public function addArgumentResolvers(array $argumentResolvers): void {
    $this->argumentResolvers = $argumentResolvers;
  }

}
