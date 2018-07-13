<?php

namespace ARouter\Routing\Exception;

/**
 * Indicates that RouteHandler have less method arguments than needed.
 */
class MissingArgumentHandlerException extends \Exception {

  /**
   * Controller object.
   *
   * @var object
   */
  private $controller;

  /**
   * Controller method name.
   *
   * @var string
   */
  private $methodName;

  /**
   * Method parameter name.
   *
   * @var string
   */
  private $parameterName;

  /**
   * MissingArgumentHandlerException constructor.
   *
   * @param object $controller
   *   Controller object.
   * @param string $methodName
   *   Controller method name.
   * @param string $parameterName
   *   Method parameter name.
   */
  public function __construct($controller, string $methodName, string $parameterName) {
    $this->controller = $controller;
    $this->methodName = $methodName;
    $this->parameterName = $parameterName;

    $controllerClass = get_class($controller);
    parent::__construct("Argument for '$parameterName' parameter is missing in $controllerClass::$methodName");
  }

  /**
   * Get controller object.
   *
   * @return object
   *   Controller object.
   */
  public function getController() {
    return $this->controller;
  }

  /**
   * Get controller method name.
   *
   * @return string
   *   Controller method name.
   */
  public function getMethodName(): string {
    return $this->methodName;
  }

  /**
   * Get method parameter name.
   *
   * @return string
   *   Method parameter name.
   */
  public function getParameterName(): string {
    return $this->parameterName;
  }

}
