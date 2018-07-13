<?php

namespace ARouter\Routing\Cache;

use ARouter\Routing\Router;

/**
 * Cache discovered route mappings to a file.
 *
 * Significantly speeds up the router.
 * In order to clear cached mappings
 * you need to call `CachedRouter::clearCache()` method.
 */
class CachedRouter extends Router {

  /**
   * Name of file that will contain serialized route mappings.
   *
   * @var string
   */
  private $cacheFileName = 'cachedRouteMappings.data';

  /**
   * CachedRouter constructor.
   *
   * @param string $cacheFileName
   *   Cache file name.
   * @param bool $resetCache
   *   Resets the cache if set to true.
   */
  public function __construct(string $cacheFileName = NULL, $resetCache = FALSE) {
    if (!empty($cacheFileName)) {
      $this->cacheFileName = $cacheFileName;
    }
    if ($resetCache) {
      $this->clearCache();
    }
  }

  /**
   * {@inheritdoc}
   */
  public function discoverRouteMappings(): void {
    $cachedRouteMappings = $this->getRouteMappingsFromCache();
    if (!empty($cachedRouteMappings)) {
      $this->routeMappings = $cachedRouteMappings;
    }
    else {
      parent::discoverRouteMappings();
      $this->saveRouteMappingsToCache();
    }
  }

  /**
   * Clear route mappings cache.
   */
  public function clearCache() {
    if (file_exists($this->cacheFileName)) {
      unlink($this->cacheFileName);
    }
  }

  /**
   * Get cached route mappings if they exists.
   */
  private function getRouteMappingsFromCache() {
    if (file_exists($this->cacheFileName)) {
      $routeMappings = unserialize(file_get_contents($this->cacheFileName));
      return $routeMappings;
    }
    return NULL;
  }

  /**
   * Save discovered route mappings to the cache.
   */
  private function saveRouteMappingsToCache() {
    file_put_contents($this->cacheFileName, serialize($this->getRouteMappings()));
  }

  /**
   * Get cache file name.
   *
   * @return string
   *   Cache file name.
   */
  public function getCacheFileName(): string {
    return $this->cacheFileName;
  }

  /**
   * Set cache file name.
   *
   * @param string $cacheFileName
   *   Cache file name.
   */
  public function setCacheFileName(string $cacheFileName): void {
    $this->cacheFileName = $cacheFileName;
  }

}
