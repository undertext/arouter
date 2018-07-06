<?php

namespace ARouter\Routing;

use ARouter\Routing\Annotation\Route;
use Doctrine\Common\Annotations\AnnotationReader;

/**
 * Allows generating of URLs in a nice way.
 */
class UrlBuilder {

  /**
   * Doctrine Annotations reader.
   *
   * @var \Doctrine\Common\Annotations\AnnotationReader
   */
  private $annotationReader;

  /**
   * UrlBuilder constructor.
   */
  public function __construct() {
    $this->annotationReader = new AnnotationReader();
  }

  /**
   * Generate URL from path pattern.
   *
   * @param string $path
   *   Path pattern.
   * @param array $args
   *   Path arguments.
   *
   * @return string
   *   Generated URL.
   *
   * @throws \Exception
   *   If path argument is missing for URL generation.
   */
  public function fromPath(string $path, array $args): string {
    preg_match_all('|{([^}]*)}|', $path, $matches);
    foreach ($matches[1] as $match) {
      if (!isset($args[$match])) {
        throw new \Exception("Path argument '{$match}' is missing for URL generation from path '{$path}'");
      }
      $path = str_replace('{' . $match . '}', $args[$match], $path);
    }
    return $path;
  }

  /**
   * Generate URL from controller and method name.
   *
   * @param string $controllerClass
   *   Name of controller class.
   * @param string $method
   *   Name of controller's method.
   * @param array $args
   *   Path arguments.
   *
   * @return string
   *   Generated URL.
   */
  public function fromControllerMethod(string $controllerClass, string $method, array $args = []) {
    $annotationReader = new AnnotationReader();
    $reflectionMethod = new \ReflectionMethod($controllerClass, $method);
    $routeAnnotation = $annotationReader->getMethodAnnotation($reflectionMethod, Route::class);
    return $this->fromPath($routeAnnotation->path, $args);
  }
}
