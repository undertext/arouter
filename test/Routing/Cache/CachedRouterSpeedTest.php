<?php

namespace ARouter\Routing\Cache;

use ARouter\Routing\Router;
use PHPUnit\Framework\TestCase;

/**
 * Speed tests for cached router.
 */
class CachedRouterSpeedTest extends TestCase {

  /**
   * Test that cached router is at least 10 times faster then default one.
   */
  public function testSpeed() {
    $executionStartTime = microtime(TRUE);
    Router::build('test/Routing/Controllers');
    $executionEndTime = microtime(TRUE);
    $seconds1 = $executionEndTime - $executionStartTime;

    CachedRouter::build('test/Routing/Controllers');
    $executionStartTime = microtime(TRUE);
    CachedRouter::build('test/Routing/Controllers');
    $executionEndTime = microtime(TRUE);
    $seconds2 = $executionEndTime - $executionStartTime;

    $this->assertLessThan($seconds1 / 10, $seconds2);
  }

}
