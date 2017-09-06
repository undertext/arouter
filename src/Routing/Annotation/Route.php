<?php

namespace ARouter\Routing\Annotation;

use Doctrine\Common\Annotations\Annotation\Required;

/**
 * Annotation for mapping web requests onto specific handler methods.
 *
 * Here is an example of simple controller with mapping of 'contacts()' method
 * to path '/contacts':
 *```php
 * class MyController {
 * /**
 *  * @Route (path="/contacts", method="GET")
 *  * /
 * public function contacts() {
 *  echo 'This is our contacts page.';
 *  }
 * }
 *
 * @Annotation
 * @Target("METHOD")
 */
class Route {

  /**
   * Web request path.
   *
   * @var string
   * @Required
   */
  public $path;

  /**
   * Web request method.
   *
   * @Enum({"GET", "POST", "HEAD", "OPTIONS", "PUT", "PATCH", "DELETE", "TRACE"})
   */
  public $method;
}
