<?php

namespace ARouter\Routing\Controllers\SubDirectory;

use ARouter\Routing\Annotation\Controller;
use ARouter\Routing\Annotation\Route;


/**
 * @Controller
 */
class TestController2 {

  /**
   * @Route(path="testpath2")
   */
  public function action2() {
    return NULL;
  }

}
