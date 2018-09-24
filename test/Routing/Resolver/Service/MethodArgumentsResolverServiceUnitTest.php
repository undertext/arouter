<?php

namespace ARouter\Routing\Resolver\Service;

use ARouter\Routing\Resolver\MethodArgumentResolver;
use ARouter\Routing\RouteMapping;
use Mockery;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;

class MethodArgumentsResolverServiceUnitTest extends TestCase {

  /**
   * @covers \ARouter\Routing\Resolver\Service\MethodArgumentsResolverService
   */
  public function testResolveArguments() {
    $argumentResolver = Mockery::mock(MethodArgumentResolver::class);
    $argumentResolver->shouldReceive('resolve')->andReturn([
      'arg1' => 'arg1value',
    ]);
    $argumentResolver2 = Mockery::mock(MethodArgumentResolver::class);
    $argumentResolver2->shouldReceive('resolve')->andReturn([
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

    $param1 = Mockery::mock(\ReflectionParameter::class);
    $param1->shouldReceive('getName')->andReturn('arg1');

    $param2 = Mockery::mock(\ReflectionParameter::class);
    $param2->shouldReceive('getName')->andReturn('arg2');

    $request =  Mockery::mock(ServerRequestInterface::class);
    $routeMapping =  Mockery::mock(RouteMapping::class);

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
