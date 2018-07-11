<?php

namespace ARouter\Routing\Annotation;

use Doctrine\Common\Annotations\Annotation\Required;

/**
 * Annotation that allows to bind a method parameter to the request header.
 *
 * You can see how method parameter $userAgent is assigned to a 'User-Agent'
 * header in next example:
 *   ```php
 * @RequestHeader (for="userAgent", from="User-Agent")
 * public function something($userAgent) {}
 * ```
 *
 * @Annotation
 * @Target("ALL")
 */
class RequestHeader {

  /**
   * Method parameter name.
   *
   * @Required
   * @var string
   */
  public $for;

  /**
   * Request header name.
   *
   * @Required
   * @var string
   */
  public $from;

  /**
   * RequestHeader constructor.
   *
   * @param array $options
   *   Annotation values.
   */
  public function __construct(array $options) {
    $this->for = $options['for'];
    $this->from = $options['from'] ?? $options['for'];
  }

}
