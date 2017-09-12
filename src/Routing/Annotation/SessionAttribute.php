<?php

namespace ARouter\Routing\Annotation;

use Doctrine\Common\Annotations\Annotation\Required;

/**
 * Annotation that allows to bind a session attribute to method argument.
 *
 * You can see how method argument $user is assigned to a $_SESSION['user']
 * in next example:
 *   ```php
 * @SessionAttribute (for="user")
 * public function sayHello($user) {}
 * ```
 * If the name of session attribute is different from argument name
 * (e.g. $_SESSION['user_value']) use next syntax:
 *   ```php
 * @SessionAttribute (for="user", from="user_value")
 * public function sayHello($user) {}
 * ```
 *
 * @Annotation
 * @Target("METHOD")
 */
class SessionAttribute {

  /**
   * Method argument name.
   *
   * @Required
   * @var string
   */
  public $for;

  /**
   * Session attribute name.
   *
   * @var string
   */
  public $from;

  /**
   * SessionAttribute constructor.
   *
   * @param array $options
   *   Annotation values.
   */
  public function __construct(array $options) {
    $this->for = $options['for'];
    $this->from = $options['from'] ?? $options['for'];
  }

}
