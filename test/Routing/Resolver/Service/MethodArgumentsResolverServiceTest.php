<?php

namespace ARouter\Routing\Resolver\Service;

use ARouter\Routing\Resolver\MethodArgumentResolver;
use ARouter\Routing\RouteMapping;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;

class MethodArgumentsResolverServiceTest extends TestCase {


  public function testResolveArguments() {
    $argumentResolver = $this->createMock(MethodArgumentResolver::class);
    $argumentResolver->method('resolve')->willReturn([
      'arg1' => 'arg1value',
    ]);
    $argumentResolver2 = $this->createMock(MethodArgumentResolver::class);
    $argumentResolver2->method('resolve')->willReturn([
      'arg2' => 'arg2value',
    ]);


    $resolverService = new MethodArgumentsResolverService();
    $resolverService->addArgumentResolvers([
      $argumentResolver,
      $argumentResolver2,
    ]);
    $this->assertEquals($resolverService->getArgumentResolvers(), [
      $argumentResolver,
      $argumentResolver2,
    ]);

    $param1 = $this->createMock(\ReflectionParameter::class);
    $param1->method('getName')->willReturn('arg1');

    $param2 = $this->createMock(\ReflectionParameter::class);
    $param2->method('getName')->willReturn('arg2');

    $request = $this->createMock(ServerRequestInterface::class);
    $routeMapping = $this->createMock(RouteMapping::class);

    $resolvedArguments = $resolverService->resolveArguments([
      $param1,
      $param2,
    ], $routeMapping, $request);

    $this->assertEquals($resolvedArguments, [
      'arg1' => 'arg1value',
      'arg2' => 'arg2value',
    ]);
  }


}
