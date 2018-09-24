<?php

namespace ARouter\Routing\HttpMessageConverter;

use ARouter\Routing\Exception\ApplicableConverterNotFoundException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Class responsible for managing HTTP message converters.
 */
class HttpMessageConverterManager {

  /**
   * Array of HTTP message converters.
   *
   * @var \ARouter\Routing\HttpMessageConverter\HttpMessageConverterInterface[]
   */
  private $converters = [];

  /**
   * Go throw each of converters and try to convert given object to Response.
   *
   * @param object $object
   *   Object for conversion to Response.
   * @param \Psr\Http\Message\RequestInterface $request
   *   Request object.
   *
   * @return \Psr\Http\Message\ResponseInterface
   *   Response object.
   *
   * @throws \ARouter\Routing\Exception\ApplicableConverterNotFoundException
   *   When applicable converter is not found.
   */
  public function convertToResponse($object, RequestInterface $request): ResponseInterface {
    $converter = $this->getApplicableConverter($request);
    if (empty($converter)) {
      throw new ApplicableConverterNotFoundException($object, $this->getConverters());
    }
    $response = $converter->toResponse($object);
    return $response;
  }

  /**
   * Pick up the correct converter for incoming request.
   *
   * @param \Psr\Http\Message\RequestInterface $request
   *   Incoming request.
   *
   * @return \ARouter\Routing\HttpMessageConverter\HttpMessageConverterInterface|null
   *   Correct HTTP message converter or NULL.
   */
  protected function getApplicableConverter(RequestInterface $request): ?HttpMessageConverterInterface {
    $acceptHeader = $request->getHeader('Accept');
    if (!empty($acceptHeader)) {
      $acceptHeaderValues = explode(',', $acceptHeader[0]);
      foreach ($acceptHeaderValues as &$acceptHeaderValue) {
        $acceptHeaderValue = explode(';', $acceptHeaderValue)[0];
      }
    }
    foreach ($this->converters as $converter) {
      $formats = $converter->getFormats();
      if (empty($acceptHeader) || !empty(array_intersect($formats, $acceptHeaderValues))) {
        return $converter;
      }
    }
    return NULL;
  }

  /**
   * Get HTTP message converters.
   *
   * @return \ARouter\Routing\HttpMessageConverter\HttpMessageConverterInterface[]
   */
  public function getConverters(): array {
    return $this->converters;
  }

  /**
   * Add HTTP message converters.
   *
   * @param \ARouter\Routing\HttpMessageConverter\HttpMessageConverterInterface[] $converters
   *   HTTP message converters.
   */
  public function addConverters(array $converters): void {
    $this->converters = array_merge($this->converters, $converters);
  }

}
