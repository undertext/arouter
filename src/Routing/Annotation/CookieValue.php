<?php

namespace ARouter\Routing\Annotation;

use Doctrine\Common\Annotations\Annotation\Required;

/**
 * @addtogroup argument_resolvers
 *
 * ## Cookie argument resolver
 * `CookieValue` Annotation allows to bind cookie value to method parameter.
 *
 * You can see how method parameter $user is assigned to a $_COOKIE['user']
 * in next example:
 * ```php
 * @CookieValue (for="user")
 * public function sayHello($user) {}
 * ```
 * If the name of cookie is different from argument name
 * (e.g. $_COOKIE['user_value']) use next syntax:
 * ```php
 * @CookieValue (for="user", from="user_value")
 * public function sayHello($user) {}
 * ```
 */

/**
 * @Annotation
 * @Target("METHOD")
 *
 * @see \ARouter\Routing\Resolver\CookieValueArgumentResolver
 */
class CookieValue {

  /**
   * Method parameter name.
   *
   * @Required
   * @var string
   */
  public $for;

  /**
   * Cookie name.
   *
   * @var string
   */
  public $from;

  /**
   * CookieValue constructor.
   *
   * @param array $options
   *   Annotation values.
   */
  public function __construct(array $options) {
    $this->for = $options['for'];
    $this->from = $options['from'] ?? $options['for'];
  }

}
