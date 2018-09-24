<?php

namespace ARouter\Routing\Cache;

use ARouter\Routing\Scanner\CachedRouteMappingsScanner;
use ARouter\Routing\Scanner\RouteMappingsScannerInterface;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;

/**
 * Tests CachedRouteMappingsScanner class.
 */
class CachedRouteMappingsScannerUnitTest extends MockeryTestCase {

  /**
   * Fake Scanner.
   *
   * @var \Mockery\MockInterface
   */
  private $scannerSpy;

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    $this->scannerSpy = Mockery::spy(RouteMappingsScannerInterface::class);
  }

  /**
   * @covers \ARouter\Routing\Scanner\CachedRouteMappingsScanner
   */
  public function testConstruction() {
    file_put_contents('cacheFileName.data', 'Data');
    $cachedScanner = new CachedRouteMappingsScanner($this->scannerSpy, 'cacheFileName.data', FALSE);
    $this->assertEquals($cachedScanner->getCacheFilePath(), 'cacheFileName.data');
    $this->assertFileExists('cacheFileName.data');

    $cachedScanner = new CachedRouteMappingsScanner($this->scannerSpy, 'cacheFileName.data', TRUE);
    $this->assertEquals($cachedScanner->getCacheFilePath(), 'cacheFileName.data');
    $this->assertFileNotExists('cacheFileName.data');
  }

  /**
   * @covers \ARouter\Routing\Scanner\CachedRouteMappingsScanner
   */
  public function testDiscoverRouteMappings() {
    file_put_contents('cacheFileName.data', 'a:0:{}');
    $cachedScanner = new CachedRouteMappingsScanner($this->scannerSpy, 'cacheFileName.data', FALSE);
    $routeMappings = $cachedScanner->discoverRouteMappings();
    $this->assertEquals($routeMappings, []);
    $this->scannerSpy->shouldNotHaveReceived('discoverRouteMappings');

    unlink('cacheFileName.data');
    $cachedScanner->discoverRouteMappings();
    $this->scannerSpy->shouldHaveReceived('discoverRouteMappings');
  }

}
