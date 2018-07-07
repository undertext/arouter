<?php

namespace ARouter\Routing\HttpMessageConverter;


use Psr\Http\Message\RequestInterface;

/**
 * Class responsible for managing HTTP message converters.
 */
class HttpMessageConverterManager {

  /**
   * @var \ARouter\Routing\HttpMessageConverter\HttpMessageConverterInterface[]
   */
  private $converters;

  /**
   * Pick up the correct converter for incoming request.
   *
   * @param \Psr\Http\Message\RequestInterface $request
   *   Incoming request.
   *
   * @return \ARouter\Routing\HttpMessageConverter\HttpMessageConverterInterface
   *   Correct HTTP message converter.
   */
  public function getApplicableConverter(RequestInterface $request): ?HttpMessageConverterInterface {
    foreach ($this->converters as $converter) {
      $acceptHeader = $request->getHeader('Accept');
      $format = $converter->getFormat();
      if (empty($acceptHeader) || in_array($format, $acceptHeader)) {
        return $converter;
      }
    }
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
   * Add HTTP message converter.
   *
   * @param \ARouter\Routing\HttpMessageConverter\HttpMessageConverterInterface $converter
   */
  public function addConverter($converter): void {
    $this->converters[] = $converter;
  }

}
