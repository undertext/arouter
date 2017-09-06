<?php

namespace ARouter\Routing;

/**
 * Mapping between route path and associated controller action method.
 */
class RouteMapping {

  /**
   * Route path.
   *
   * @var string
   */
  private $path;

  /**
   * Name of the controller class.
   *
   * @var string
   */
  private $controller;

  /**
   *   Name of the controller method.
   *
   * @var string
   */
  private $method;

  /**
   * Annotations on the controller's method.
   *
   * @var object[]
   */
  private $annotations;


  /**
   * RouteMapping constructor.
   *
   * @param string $path
   *  Route path.
   * @param string $controller
   *   Name of the controller class.
   * @param string $method
   *   Name of the controller method.
   * @param array $annotations
   *   Annotations on the method
   */
  public function __construct($path, $controller, $method, $annotations = []) {
    $this->path = $path;
    $this->method = $method;
    $this->controller = $controller;
    $this->annotations = $annotations;
  }

  /**
   * Get route path.
   *
   * @return string
   *  Route path.
   */
  public function getPath(): string {
    return $this->path;
  }

  /**
   * Get name of the controller class.
   *
   * @return string
   *   Name of the controller class.
   */
  public function getController(): string {
    return $this->controller;
  }

  /**
   *  Get name of the controller method.
   *
   * @return string
   *   Name of the controller method.
   */
  public function getMethod(): string {
    return $this->method;
  }

  /**
   * Get annotations on the controller's method.
   *
   * @return object[]
   *   Annotations on the controller's method.
   */
  public function getAnnotations(): array {
    return $this->annotations;
  }

}
