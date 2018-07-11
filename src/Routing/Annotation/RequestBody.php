<?php

namespace ARouter\Routing\Annotation;

use Doctrine\Common\Annotations\Annotation\Required;

/**
 * Annotation that allows to bind a method parameter to the body of web request.
 *
 * You can see how method parameter $json is assigned to a JSON web request body
 * in next example:
 *   ```php
 * @RequestBody (for="json")
 * public function something($json) { $decoded_json = json_decode($json); }
 * ```
 *
 * @Annotation
 * @Target("ALL")
 */
class RequestBody {

  /**
   * Method parameter name.
   *
   * @Required
   * @var string
   */
  public $for;

}
