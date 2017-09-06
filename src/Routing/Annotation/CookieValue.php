<?php

namespace ARouter\Routing\Annotation;

use Doctrine\Common\Annotations\Annotation\Required;

/**
 * Annotation that allows to bind cookie value to method argument.
 *
 * You can see how method argument $user is assigned to a $_COOKIE['user']
 * in next example:
 *   ```php
 * @CookieValue (for = 'user')
 * public function sayHello($user) {}
 * ```
 * If the name of cookie is different from argument name
 * (e.g. $_COOKIE['user_value']) use next syntax:
 *   ```php
 * @CookieValue (for = 'user', from = "user_value")
 * public function sayHello($user) {}
 * ```
 *
 * @Annotation
 * @Target("METHOD")
 */
class CookieValue {

  /**
   * Method argument name.
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
