<?php

namespace ARouter\Routing\Exception;

/**
 * @addtogroup exceptions
 *
 * ## ApplicableConverterNotFoundException
 * Indicates that applicable HTTP message converter was not found.
 * Happens when controller method returns something else than ResponseInterface
 * object and applicable converter is not registered in router.
 * You can pass custom converters on `RouterFactory::getRouter()` call.
 *
 * @see HttpMessageConverterManager
 */
class ApplicableConverterNotFoundException extends \Exception {

  /**
   * Object for conversion.
   *
   * @var object
   */
  private $objectForConversion;

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
  public function __construct($objectForConversion, array $converters) {
    $this->objectForConversion = $objectForConversion;
    $this->converters = $converters;

    $convertersNamesMessage = array_map(function ($converter) {
      return get_class($converter);
    }, $converters);
    $convertersNamesMessage = implode(',', $convertersNamesMessage);
    if (empty($convertersNamesMessage)) {
      $convertersNamesMessage = 'There are no converters';
    }
    $message = "Applicable HTTP message Converter not found. Available converters : $convertersNamesMessage";
    parent::__construct($message);
  }

  /**
   * Get object for conversion.
   *
   * @return object
   *   Object for conversion.
   */
  public function getObjectForConversion() {
    return $this->objectForConversion;
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
