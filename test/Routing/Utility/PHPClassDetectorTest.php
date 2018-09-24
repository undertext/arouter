<?php

namespace ARouter\Routing\Utility;

use PHPUnit\Framework\TestCase;
use ARouter\Routing\Controllers\SubDirectory\TestController2;
use ARouter\Routing\Controllers\TestController;

/**
 * Tests PHPClassDetector.
 */
class PHPClassDetectorTest extends TestCase {

  /**
   * @covers \ARouter\Routing\Utility\PHPClassesDetector
   */
  public function testScan() {
    $pathBasedClassScanner = new PHPClassesDetector();
    $result = $pathBasedClassScanner->detect(__DIR__ . '/../../Routing/Controllers');
    self::assertEquals([
      TestController2::class,
      TestController::class,
    ], $result);

  }

}
