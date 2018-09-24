<?php

namespace ARouter\Routing\Exception;


/**
 * @addtogroup exceptions
 *
 * ##PathArgumentIsMissingException
 * Indicates that required path argument is missing in request path.
 * Example: you have "/users/{name}" mapping but trying to send request
 * to '/users' .
 *
 * @see PathArgumentResolver
 */
class PathArgumentIsMissingException extends \Exception {

  /**
   * Argument name.
   *
   * @var string
   */
  private $argumentName;

  /**
   * Request path.
   *
   * @var string
   */
  private $path;

  /**
   * PathArgumentIsMissingException constructor.
   *
   * @param string $argumentName
   *   Argument name.
   * @param string $path
   *   Request path.
   */
  public function __construct($argumentName, $path) {
    $this->argumentName = $argumentName;
    $this->path = $path;

    parent::__construct("Path argument '{$argumentName}' is missing for URL generation from path '{$path}'");
  }

  /**
   * Get argument name.
   *
   * @return string
   */
  public function getArgumentName(): string {
    return $this->argumentName;
  }

  /**
   * Get request path.
   *
   * @return string
   */
  public function getPath(): string {
    return $this->path;
  }

}
