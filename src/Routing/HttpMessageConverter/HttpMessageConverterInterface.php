<?php

namespace ARouter\Routing\HttpMessageConverter;

use Psr\Http\Message\ResponseInterface;

/**
 * Used to define HTTP message converters.
 *
 * The goal of the converter is to convert object/scalar
 * to HTTP response object. This way we can return objects in controller methods
 * and they will be automatically converted to Response objects.
 */
interface HttpMessageConverterInterface {

  /**
   * Get response formats supported by this converter.
   *
   * @return string[]
   */
  public function getFormats(): array;

  /**
   * Covert object to Response object and return it.
   *
   * @param object $object
   *   Any object that can be convertable to Response.
   *
   * @return \Psr\Http\Message\ResponseInterface
   *   Response object.
   */
  public function toResponse($object): ResponseInterface;

}
