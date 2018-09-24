<?php

namespace ARouter\Routing;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Every router should implement this interface.
 */
interface RouterInterface {

  /**
   * Get HTTP response object for given request.
   *
   * @param \Psr\Http\Message\ServerRequestInterface $request
   *   HTTP request.
   *
   * @return \Psr\Http\Message\ResponseInterface
   *   HTTP response object for given request.
   */
  public function getResponse(ServerRequestInterface $request): ResponseInterface;

}
