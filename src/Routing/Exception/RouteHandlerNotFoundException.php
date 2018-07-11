<?php

namespace ARouter\Routing\Exception;

use Psr\Http\Message\RequestInterface;

/**
 * Indicates that RouteHandler not found for incoming request.
 */
class RouteHandlerNotFoundException extends \Exception {

  /**
   * Request object.
   *
   * @var \Psr\Http\Message\RequestInterface
   */
  private $request;

  /**
   * RouteHandlerNotFoundException constructor.
   *
   * @param $request
   */
  public function __construct(RequestInterface $request) {
    $this->request = $request;

    $path = $request->getUri()->getPath();
    $method = $request->getMethod();
    parent::__construct("Route handler not found for $method request to '$path' path");
  }


  /**
   * Get request object.
   *
   * @return RequestInterface
   *   Request object.
   */
  public function getRequest(): RequestInterface {
    return $this->request;
  }

}
