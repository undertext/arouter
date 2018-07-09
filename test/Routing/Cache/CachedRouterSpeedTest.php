<?php

namespace ARouter\Routing\Cache;


use ARouter\Routing\Router;
use PHPUnit\Framework\TestCase;

class CachedRouterSpeedTest extends TestCase {

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
