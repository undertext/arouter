<?php

namespace ARouter\Routing\Factory;


use ARouter\Routing\HttpMessageConverter\HttpMessageConverterManager;
use ARouter\Routing\Resolver\CookieValueArgumentResolver;
use ARouter\Routing\Resolver\PathArgumentResolver;
use ARouter\Routing\Resolver\RequestArgumentResolver;
use ARouter\Routing\Resolver\RequestBodyArgumentResolver;
use ARouter\Routing\Resolver\RequestHeaderArgumentResolver;
use ARouter\Routing\Resolver\RequestParamArgumentResolver;
use ARouter\Routing\Resolver\Service\MethodArgumentsResolverService;
use ARouter\Routing\Resolver\SessionAttributeArgumentResolver;
use ARouter\Routing\Router;
use ARouter\Routing\Scanner\AnnotationRouteMappingsScanner;
use ARouter\Routing\Scanner\CachedRouteMappingsScanner;
use ARouter\Routing\Scanner\RouteMappingsScannerInterface;
use ARouter\Routing\Utility\PHPClassesDetector;
use Doctrine\Common\Annotations\AnnotationReader;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

/**
 * @runTestsInSeparateProcesses
 */
class RouterFactoryUnitTest extends TestCase {

  /**
   * @covers \ARouter\Routing\Factory\RouterFactory
   */
  public function testGetRouter() {
    $scannerMock = Mockery::mock('overload:' . AnnotationRouteMappingsScanner::class, RouteMappingsScannerInterface::class);
    $this->registerScannerExpectations($scannerMock);

    $resolverServiceMock = Mockery::mock('overload:' . MethodArgumentsResolverService::class);
    $this->registerResolverServiceExpectations($resolverServiceMock);

    $routerMock = Mockery::mock('overload:' . Router::class);
    $routerMock->shouldReceive('__construct')->withArgs(
      function (AnnotationRouteMappingsScanner $scanner, MethodArgumentsResolverService $resolverService, HttpMessageConverterManager $converterManager) {
        $this->assertEquals(get_class($scanner), AnnotationRouteMappingsScanner::class);
        $this->assertEquals(get_class($resolverService), MethodArgumentsResolverService::class);
        $this->assertEquals(get_class($converterManager), HttpMessageConverterManager::class);
        return TRUE;
      }
    );

    RouterFactory::getRouter('directory', [], []);
  }

  /**
   * @covers \ARouter\Routing\Factory\RouterFactory
   */
  public function testGetCachedRouter() {
    $scannerMock = Mockery::mock('overload:' . AnnotationRouteMappingsScanner::class, RouteMappingsScannerInterface::class);
    $this->registerScannerExpectations($scannerMock);

    $resolverServiceMock = Mockery::mock('overload:' . MethodArgumentsResolverService::class);
    $this->registerResolverServiceExpectations($resolverServiceMock);

    $routerMock = Mockery::mock('overload:' . Router::class);
    $routerMock->shouldReceive('__construct')->withArgs(
      function (RouteMappingsScannerInterface $scanner, MethodArgumentsResolverService $resolverService, HttpMessageConverterManager $converterManager) {
        $this->assertEquals(get_class($scanner), CachedRouteMappingsScanner::class);
        $this->assertEquals(get_class($resolverService), MethodArgumentsResolverService::class);
        $this->assertEquals(get_class($converterManager), HttpMessageConverterManager::class);
        return TRUE;
      }
    );

    RouterFactory::getCachedRouter('directory');
  }

  private function registerScannerExpectations(MockInterface $scannerMock) {
    $scannerMock->shouldReceive('__construct')->withArgs(
      function ($directory, $annotationsReader, $phpClassDetector) {
        $this->assertEquals($directory, 'directory');
        $this->assertEquals(get_class($annotationsReader), AnnotationReader::class);
        $this->assertEquals(get_class($phpClassDetector), PHPClassesDetector::class);
        return TRUE;
      }
    );
  }

  private function registerResolverServiceExpectations(MockInterface $resolverServiceMock) {
    $resolverServiceMock->shouldReceive('addArgumentResolvers')
      ->withArgs(function ($resolvers) {
        $count = 0;
        $resolversClasses = [
          RequestArgumentResolver::class,
          RequestParamArgumentResolver::class,
          PathArgumentResolver::class,
          RequestBodyArgumentResolver::class,
          CookieValueArgumentResolver::class,
          RequestHeaderArgumentResolver::class,
          SessionAttributeArgumentResolver::class,
        ];
        foreach ($resolvers as $resolver) {
          if (!in_array(get_class($resolver), $resolversClasses)) {
            $this->fail();
          }
          $count++;
        }

        if ($count !== 7) {
          $this->fail();
        }
        return TRUE;
      });
  }
}
