<?php

namespace ARouter\Routing;

use Psr\Http\Message\ResponseInterface;
use ReflectionMethod;
use ARouter\Routing\Exception\MissingArgumentHandlerException;

/**
 * Handler for producing HTTP response from controller action methods.
 *
 * Route handler calls provided controller method with provided arguments.
 * Router is responsible for creating correct RouteHandler object for
 * incoming request.
 *
 * Action method arguments are passed as array in form of
 *  <code>['argument name' => 'argument value']</code>.
 *
 * E.g. If you have <code>MyController</code> class with method
 * <code>function contactPage($name, $message)</code>
 * the correct handler creation code will be
 * ```php
 * new RouteHandler(new MyController(), 'contactPage',
 * ['name' => 'Foo', 'message' => 'Bar'])'
 * ```
 *
 * If you provide less arguments than needed the exception will be thrown on
 * handler creation.
 *
 * @see \ARouter\Routing\Router
 */
class RouteHandler {

  /**
   * Controller object.
   *
   * @var object
   */
  private $controller;

  /**
   * Name of controller method.
   *
   * @var string
   */
  private $method;

  /**
   * Controller method arguments in form of
   *  <code>['argument name' => 'argument value']</code>.
   *
   * @var array
   */
  private $keyedArgs = [];

  /**
   * RouteHandler constructor.
   *
   * @param object $controller
   *   Controller object.
   * @param string $method
   *   Name of controller method.
   * @param array $keyedArgs
   *   Controller method arguments in form of
   *  <code>['argument name' => 'argument value']</code>.
   */
  public function __construct($controller, $method, $keyedArgs = []) {
    $this->controller = $controller;
    $this->method = $method;
    $this->keyedArgs = $keyedArgs;
  }

  /**
   * Add additional controller method arguments.
   *
   * @param array $args
   *   Controller method arguments.
   */
  public function addArguments(array $args): void {
    $this->keyedArgs = array_merge($this->keyedArgs, $args);
  }

  /**
   * Convert controller method keyed arguments to form compatible with
   * <code>  call_user_func_array </code>
   *
   * @param array $keyedArgs
   *   Controller method keyed arguments.
   *
   * @return array
   *   Converted arguments.
   *
   * @throws \ARouter\Routing\Exception\MissingArgumentHandlerException
   */
  public function convertKeyedArgs(array $keyedArgs): array {
    $reflectionMethod = new ReflectionMethod($this->controller, $this->method);
    $convertedArgs = [];
    foreach ($reflectionMethod->getParameters() as $param) {
      if (isset($keyedArgs[$param->getName()])) {
        $convertedArgs[] = $keyedArgs[$param->getName()];
      }
      else {
        if ($param->isDefaultValueAvailable()) {
          $convertedArgs[] = $param->getDefaultValue();
        }
        else {
          $controllerClass = get_class($this->controller);
          throw new MissingArgumentHandlerException("Missing {$param->getName()} argument for
          {$controllerClass}::{$this->method}");
        }
      }
    }
    return $convertedArgs;
  }

  /**
   * Execute route handler and return result.
   */
  public function execute() {
    $arguments = $this->convertKeyedArgs($this->keyedArgs);
    $response = call_user_func_array([$this->controller, $this->method], $arguments);
    return $response;
  }

  /**
   * Get controller object.
   *
   * @return object
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
  public function getMethod(): string {
    return $this->method;
  }

  /**
   *  Get method arguments are passed as array in form of
   *  <code>['argument name' => 'argument value']</code>.
   *
   * @return array
   *   Keyed method arguments.
   */
  public function getKeyedArgs(): array {
    return $this->keyedArgs;
  }

}
