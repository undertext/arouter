<?php

namespace ARouter\Routing\Resolver;

use Mockery;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use ARouter\Routing\Controllers\TestController;

/**
 * Base class for testing argument resolvers.
 */
abstract class ArgumentResolverTestBase extends TestCase {

  /**
   * Request object mock.
   *
   * @var \Psr\Http\Message\ServerRequestInterface
   */
  protected $request;

  /**
   * TestController method parameters.
   *
   * @var \ReflectionParameter[]
   */
  protected $testControllerKeyedMethodParams;

  /**
   * ArgumentResolverTestBase constructor.
   *
   * @throws \ReflectionException
   */
  public function setUp() {
    $this->request = Mockery::mock(ServerRequestInterface::class);
    $keyedMethodParams = [];
    $reflectionMethod = new \ReflectionMethod(TestController::class, 'argumentResolversPath');
    foreach ($reflectionMethod->getParameters() as $methodParam) {
      $keyedMethodParams[$methodParam->name] = $methodParam;
    }
    $this->testControllerKeyedMethodParams = $keyedMethodParams;
  }

}
