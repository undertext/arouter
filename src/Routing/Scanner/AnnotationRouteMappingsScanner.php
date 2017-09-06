<?php

namespace ARouter\Routing\Scanner;

use ARouter\Routing\RouteMapping;
use ARouter\Routing\Utility\PHPClassesDetector;
use Doctrine\Common\Annotations\AnnotationReader;
use ARouter\Routing\Annotation\Controller;
use ARouter\Routing\Annotation\Route;
use Doctrine\Common\Annotations\AnnotationRegistry;

/**
 *
 * Detect route mappings by annotation.
 *
 * Declare your controller class with @Controller annotation
 * and you action method with @Route annotation and it will be picked up by
 * this scanner.
 */
class AnnotationRouteMappingsScanner implements RouteMappingsScannerInterface {

  /**
   * Directory where controllers are stored.
   *
   * @var string
   */
  private $controllersDirectory;

  /**
   * Doctrine Annotations reader.
   *
   * @var AnnotationReader
   */
  private $annotationReader;

  /**
   * PHP classes detector.
   *
   * @var \ARouter\Routing\Utility\PHPClassesDetector
   */
  private $phpClassesDetector;

  /**
   * AnnotationRouteMappingsScanner constructor.
   *
   * @param $controllersDirectory
   *   Directory where controllers are located.
   */
  public function __construct(string $controllersDirectory) {
    AnnotationRegistry::registerLoader('class_exists');
    $this->controllersDirectory = $controllersDirectory;
    $this->annotationReader = new AnnotationReader();
    $this->phpClassesDetector = new PHPClassesDetector();
  }

  /**
   * Discover mappings between routes and controller action methods.
   *
   * @return \ARouter\Routing\RouteMapping[]
   *   Discovered route mappings.
   */
  public function discoverRouteMappings(): array {
    $routeMappings = [];
    $controllers = $this->discoverControllers();
    foreach ($controllers as $controller) {
      $routeMappings = array_merge($routeMappings, $this->getRouteMappingsForController($controller));
    }
    return $routeMappings;
  }

  /**
   * Discover controllers.
   *
   * Discover classes with @Controller annotation from controller's directory.
   *
   * @return \ReflectionClass[]
   *   Detected controllers.
   */
  protected function discoverControllers(): array {
    $controllers = [];
    $controllerClasses = $this->phpClassesDetector->detect($this->controllersDirectory);
    foreach ($controllerClasses as $controllerClass) {
      $reflectionClass = new \ReflectionClass($controllerClass);
      $controllerAnnotation = $this->annotationReader->getClassAnnotation($reflectionClass, Controller::class);
      if ($controllerAnnotation) {
        $controllers[] = $reflectionClass;
      }
    }

    return $controllers;
  }

  /**
   * Get route mappings from action methods based on @Route annotation.
   *
   * @param \ReflectionClass $controllerClass
   *   Controller class.
   *
   * @return \ARouter\Routing\RouteMapping[]
   *   Route mappings.
   */
  protected function getRouteMappingsForController(\ReflectionClass $controllerClass): array {
    $controllerRoutesMapping = [];
    foreach ($controllerClass->getMethods() as $method) {
      $routeAnnotation = $this->annotationReader->getMethodAnnotation($method, Route::class);
      if ($routeAnnotation) {
        $controllerRoutesMapping[] = new RouteMapping($routeAnnotation->path, $controllerClass->getName(), $method->getName(), $this->annotationReader->getMethodAnnotations($method));
      }
    }
    return $controllerRoutesMapping;
  }

}
