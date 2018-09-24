<?php

namespace ARouter\Routing\Annotation;

use Doctrine\Common\Annotations\Annotation\Required;

/**
 * @addtogroup argument_resolvers
 *
 * ## Request header resolver
 * `RequestHeader` annotation allows to bind a method parameter to the request
 *   header.
 *
 * You can see how method parameter $userAgent is assigned to a 'User-Agent'
 * header in next example:
 * ```php
 * @RequestHeader (for="userAgent", from="User-Agent")
 * public function something($userAgent) {}
 * ```
 */

/**
 * @Annotation
 * @Target("ALL")
 *
 * @see \ARouter\Routing\Resolver\RequestHeaderArgumentResolver
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

}
