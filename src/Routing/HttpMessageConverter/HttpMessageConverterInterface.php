<?php

namespace ARouter\Routing\HttpMessageConverter;

use Psr\Http\Message\ResponseInterface;

/**
 * @defgroup http_message_converters HTTP Message Converters
 *
 * We can define our own HTTP message converters by implementing
 * `HttpMessageConverterInterface`. Then we can pass our converters as argument
 * to `RouterFactory::getRouter()` .
 *
 * The goal of the converter is to convert object/scalar value
 * to HTTP response object. This way we can return objects in controller methods
 * and they will be automatically converted to Response objects.
 *
 * For example if we will create `JsonHttpMessageConverter` then in theory we
 * will have possibility to return object/arrays from our controller
 * ```php
 * public function getData(){
 *   return ['name' => 'Name', 'lastname' => 'Lastname'];
 * }
 * ```
 * and this array will be converted to JSON response with body
 * ```
 * {name: 'Name', lastname: 'Lastname'}
 * ```
 */

/**
 * Interface for Http message converters.
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
