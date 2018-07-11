<?php

namespace ARouter\Routing\Exception;

/**
 * Indicates that applicable HTTP message converter was not found.
 *
 * @codeCoverageIgnore
 */
class ApplicableConverterNotFoundException extends \Exception {

  /**
   * Object for conversion.
   *
   * @var object
   */
  private $objectForConvertion;

  /**
   * Available converters.
   *
   * @var \ARouter\Routing\HttpMessageConverter\HttpMessageConverterInterface[]
   */
  private $converters;

  /**
   * ApplicableConverterNotFoundException constructor.
   *
   * @param object $objectForConversion
   *   Object for conversion.
   * @param \ARouter\Routing\HttpMessageConverter\HttpMessageConverterInterface[] $converters
   *   Available converters.
   */
  public function __construct($objectForConversion, $converters) {
    $convertersNames = array_map(function ($converter) {
      return get_class($converter);
    }, $converters);
    $convertersNames = implode(',', $convertersNames);
    if (empty($convertersNames)) {
      $convertersNames = 'There are no converters';
    }
    $message = "Applicable HTTP message Converter not found. Available converters : $convertersNames";
    parent::__construct($message);
  }

  /**
   * Get object for conversion.
   *
   * @return object
   *   Object for conversion.
   */
  public function getObjectForConvertion(): object {
    return $this->objectForConvertion;
  }

  /**
   * Get available converters.
   *
   * @return \ARouter\Routing\HttpMessageConverter\HttpMessageConverterInterface[]
   *   Available converters.
   */
  public function getConverters(): array {
    return $this->converters;
  }

}
