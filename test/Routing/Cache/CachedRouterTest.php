<?php

namespace ARouter\Routing\Cache;

use ARouter\Routing\Router;
use PHPUnit\Framework\TestCase;

/**
 * Tests CachedRouter class.
 */
class CachedRouterTest extends TestCase {

  /**
   * Call protected/private method of a class.
   *
   * @param object &$object Instantiated object that we will run method on.
   * @param string $methodName Method name to call
   * @param array $parameters Array of parameters to pass into method.
   *
   * @return mixed Method return.
   */
  public function invokeMethod(&$object, $methodName, array $parameters = []) {
    $reflection = new \ReflectionClass(get_class($object));
    $method = $reflection->getMethod($methodName);
    $method->setAccessible(TRUE);

    return $method->invokeArgs($object, $parameters);
  }

  /**
   * @covers \ARouter\Routing\Cache\CachedRouter
   */
  public function testCachedRouter() {
    CachedRouter::clearCache();
    $cachedRouter = CachedRouter::build('test/Routing/Controllers');
    $router = Router::build('test/Routing/Controllers');
    $this->assertEquals($cachedRouter->getRouteMappings(), $router->getRouteMappings());
    $this->assertFileExists(CachedRouter::$CACHE_FILE_NAME);
    $mappingsFromCache = $this->invokeMethod($cachedRouter, 'getRouteMappingsFromCache');
    $this->assertNotEmpty($mappingsFromCache);
    $this->assertEquals($mappingsFromCache, $router->getRouteMappings());

    CachedRouter::clearCache();
    $this->assertFileNotExists(CachedRouter::$CACHE_FILE_NAME);
  }

}
