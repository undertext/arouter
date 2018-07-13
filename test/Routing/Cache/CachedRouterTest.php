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
   * @param object &$object
   *   Instantiated object that we will run method on.
   * @param string $methodName
   *   Method name to call.
   * @param array $parameters
   *   Array of parameters to pass into method.
   *
   * @return mixed
   *   Method return.
   */
  public function invokeMethod(&$object, $methodName, array $parameters = []) {
    $reflection = new \ReflectionClass(get_class($object));
    $method = $reflection->getMethod($methodName);
    $method->setAccessible(TRUE);

    return $method->invokeArgs($object, $parameters);
  }

  /**
   * Tests that cached router behave in the same way as default router.
   */
  public function testCachedRouter() {
    /** @var \ARouter\Routing\Cache\CachedRouter $cachedRouter */
    $cachedRouter = CachedRouter::build('test/Routing/Controllers', 'testCachedRoutes.data', TRUE);
    $router = Router::build('test/Routing/Controllers');
    $this->assertEquals($cachedRouter->getRouteMappings(), $router->getRouteMappings());
    $this->assertFileExists($cachedRouter->getCacheFileName());

    $cachedRouter->setCacheFileName('anotherCachedRoutes.data');
    $cachedRouter->discoverRouteMappings();
    $this->assertFileExists($cachedRouter->getCacheFileName());

    $mappingsFromCache = $this->invokeMethod($cachedRouter, 'getRouteMappingsFromCache');
    $this->assertNotEmpty($mappingsFromCache);
    $this->assertEquals($mappingsFromCache, $router->getRouteMappings());

    $cachedRouter->clearCache();
    $this->assertFileNotExists($cachedRouter->getCacheFileName());
  }

}
