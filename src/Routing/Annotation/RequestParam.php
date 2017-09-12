<?php

namespace ARouter\Routing\Annotation;

use Doctrine\Common\Annotations\Annotation\Required;

/**
 * Annotation that allows to bind a method parameter to a web request parameter.
 *
 * You can see how method argument $page is assigned to a request parameter
 * from 'example.com/list?page=2' URL in next example:
 *   ```php
 * @RequestParam (for="page")
 * public function list($page) { print "Current page is $page" }
 * ```
 * If the name of request parameter is different from argument name
 * (example.com/list?pageNum=2) you can use next syntax:
 *   ```php
 * @RequestParam (for="page", from = "pageNum")
 * public function list($page) { print "Current page is $page" }
 * ```
 *
 * If request parameter is a file you should typehint method parameter with
 *  <strong>UploadedFileInterface</strong> interface.
 *
 * @Annotation
 * @Target("ALL")
 */
class RequestParam {

  /**
   * Method argument name.
   *
   * @Required
   * @var string
   */
  public $for;

  /**
   * Web request parameter name.
   *
   * @var string
   */
  public $from;

  /**
   * RequestParam constructor.
   *
   * @param array $options
   *   Annotation values.
   */
  public function __construct(array $options) {
    $this->for = $options['for'];
    $this->from = $options['from'] ?? $options['for'];
  }

}
