<?php

namespace ARouter\Routing\Scanner;

use ARouter\Routing\Controllers\SubDirectory\TestController2;
use ARouter\Routing\Controllers\TestController;
use ARouter\Routing\RouteMappingUnitTest;
use ARouter\Routing\Utility\PHPClassesDetector;
use Doctrine\Common\Annotations\AnnotationReader;
use ARouter\Routing\Annotation\Controller;
use ARouter\Routing\Annotation\Route;
use Mockery;
use PHPUnit\Framework\TestCase;

/**
 * Tests AnnotationRouteMappingsScanner class.
 */
class AnnotationRouteMappingsScannerUnitTest extends TestCase {

  /**
   * @covers \ARouter\Routing\Scanner\AnnotationRouteMappingsScanner
   */
  public function testDiscoverRouteMappings() {
    $routeAnnotation = new Route();
    $routeAnnotation->path = 'simple-action';
    $routeAnnotation2 = new Route();
    $routeAnnotation2->path = 'simple-action-2';
    $additionalAnnotationMock = Mockery::mock();

    $annotationReaderMock = Mockery::mock(AnnotationReader::class);
    $annotationReaderMock->shouldReceive('getClassAnnotation')
      ->andReturn(new Controller());
    $annotationReaderMock->shouldReceive('getMethodAnnotation')
      ->andReturns($routeAnnotation, $routeAnnotation2);

    $annotationReaderMock->shouldReceive('getMethodAnnotations')
      ->andReturns([
        $routeAnnotation,
        $additionalAnnotationMock,
      ], [$routeAnnotation2, $additionalAnnotationMock]);

    $phpClassDetectorMock = Mockery::mock(PHPClassesDetector::class);
    $phpClassDetectorMock->shouldReceive('detect')
      ->with('Controllers')
      ->andReturn([TestController::class, TestController2::class]);

    $scanner = new AnnotationRouteMappingsScanner('Controllers', $annotationReaderMock, $phpClassDetectorMock);

    /** @var RouteMappingUnitTest[] $routeMappings */
    $routeMappings = $scanner->discoverRouteMappings();
    $this->assertCount(6, $routeMappings);

    $this->assertEquals($routeMappings[0]->getPath(), 'simple-action');
    $this->assertEquals($routeMappings[0]->getController(), TestController::class);
    $this->assertEquals($routeMappings[0]->getMethod(), 'simpleAction');
    $this->assertEquals($routeMappings[0]->getAnnotations(), [
      $routeAnnotation,
      $additionalAnnotationMock,
    ]);

    $this->assertEquals($routeMappings[1]->getPath(), 'simple-action-2');
    $this->assertEquals($routeMappings[1]->getController(), TestController::class);
    $this->assertEquals($routeMappings[1]->getMethod(), 'parameterInPathAction');
    $this->assertEquals($routeMappings[1]->getAnnotations(), [
      $routeAnnotation2,
      $additionalAnnotationMock,
    ]);
  }

}
