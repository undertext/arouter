<?php

namespace ARouter\Routing\Annotation;

use Doctrine\Common\Annotations\Annotation\Required;

/**
 * @addtogroup routes_declaring
 *
 *  ## Route annotation
 *  `Route` annotation is used for mapping web requests onto specific controller
 *   methods.
 *
 * Here is an example of simple controller with mapping of 'contacts()' method
 * to path '/contacts':
 * ```php
 * @Controller
 * class MyController {
 *
 *   @Route (path="/contacts", method="GET")
 *   public function contacts() {
 *    return new Response(200, [], "Contacts page");
 *   }
 *  }
 * ```
 *
 * If you do not specify "method" then GET will be used by default.
 *
 */

/**
 *
 * @Annotation
 * @Target("METHOD")
 *
 * @see \ARouter\Routing\Scanner\AnnotationRouteMappingsScanner
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
   * @Enum({"GET", "POST", "HEAD", "OPTIONS", "PUT", "PATCH", "DELETE",
   *   "TRACE"})
   */
  public $method = "GET";
}
