<?php

namespace ARouter\Routing\Cache;

use ARouter\Routing\Router;

/**
 * Cache discovered route mappings to a file.
 *
 * Significantly speeds up route mappings discovery.
 * In order to clear cached mappings
 * you need to call `CachedRouter::clearCache()` static method.
 */
class CachedRouter extends Router {

  /**
   * Name of file that will contain serialized route mappings.
   *
   * @var string
   */
  public static $CACHE_FILE_NAME = 'cached_route_mappings.data';

  /**
   * {@inheritdoc}
   */
  public function discoverRouteMappings(): void {
    $cachedRouteMappings = $this->getRouteMappingsFromCache();
    if (!empty($cachedRouteMappings)) {
      $this->addRouteMappings($cachedRouteMappings);
    }
    else {
      parent::discoverRouteMappings();
      $this->saveRouteMappingsToCache();
    }
  }

  /**
   * Clear route mappings cache.
   */
  public static function clearCache() {
    unlink(static::$CACHE_FILE_NAME);
  }

  /**
   * Get cached route mappings if they exists.
   */
  private function getRouteMappingsFromCache() {
    if (file_exists(static::$CACHE_FILE_NAME)) {
      $routeMappings = unserialize(file_get_contents(static::$CACHE_FILE_NAME));
      return $routeMappings;
    }
    return NULL;
  }

  /**
   * Save discovered route mappings to the cache.
   */
  private function saveRouteMappingsToCache() {
    file_put_contents(static::$CACHE_FILE_NAME, serialize($this->getRouteMappings()));
  }

}
