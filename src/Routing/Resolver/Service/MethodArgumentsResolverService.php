<?php

namespace ARouter\Routing\Resolver\Service;

/**
 * Manages argument resolvers.
 */
class MethodArgumentsResolverService {

  /**
   * Array of argument resolvers.
   *
   * @var \ARouter\Routing\Resolver\MethodArgumentResolver[]
   */
  private $argumentResolvers = [];

  /**
   * Resolve method arguments based on incoming request.
   *
   * @param \ReflectionParameter[] $methodParams
   *   Method parameters
   * @param \ARouter\Routing\RouteMatch $routeMatch
   *   Route mapping matched by incoming request.
   *
   * @return array
   *   Resolved arguments as array in format ['param_name' => 'argument_value',
   *   ...]
   */
  public function resolveArguments($methodParams, $routeMatch): array {
    $resolvedArguments = [];
    $keyedMethodParams = [];
    foreach ($methodParams as $methodParam) {
      $keyedMethodParams[$methodParam->getName()] = $methodParam;
    }
    foreach ($this->argumentResolvers as $argumentResolver) {
      $resolvedArguments = array_merge($resolvedArguments, $argumentResolver->resolve($keyedMethodParams, $routeMatch));
    }
    return $resolvedArguments;
  }

  /**
   * Get argument resolvers.
   *
   * @return \Symfony\Component\HttpKernel\Controller\ArgumentResolverInterface[]
   *   Argument resolvers;
   */
  public function getArgumentResolvers(): array {
    return $this->argumentResolvers;
  }

  /**
   * Add argument resolvers.
   *
   * @param \Symfony\Component\HttpKernel\Controller\ArgumentResolverInterface[] $argumentResolvers
   *   Argument resolvers.
   */
  public function addArgumentResolvers(array $argumentResolvers): void {
    $this->argumentResolvers = $argumentResolvers;
  }

}
