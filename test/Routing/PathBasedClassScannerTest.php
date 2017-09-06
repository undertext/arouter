<?php

namespace ARouter\Routing;

use ARouter\Routing\Utility\PHPClassesDetector;
use PHPUnit\Framework\TestCase;
use ARouter\Routing\Controllers\SubDirectory\TestController2;
use ARouter\Routing\Controllers\TestController;


class PathBasedClassScannerTest extends TestCase {


  public function testScan() {
    $pathBasedClassScanner = new PHPClassesDetector();
    $result = $pathBasedClassScanner->detect('test/Routing/Controllers');
    self::assertEquals([
      TestController2::class,
      TestController::class
    ], $result);

  }


}
