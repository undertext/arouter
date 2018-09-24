<?php

namespace ARouter\Routing\Scanner;

/**
 * Caching decorator for route mapping scanners.
 *
 * All discovered route mappings are cached in a file in filesystem.
 */
class CachedRouteMappingsScanner implements RouteMappingsScannerInterface {

  /**
   * Route mapping scanner to wrap with caching layer.
   *
   * @var \ARouter\Routing\Scanner\RouteMappingsScannerInterface
   */
  private $scanner;

  /**
   * Path to file that will contain cached route mappings.
   *
   * @var string
   */
  private $cacheFilePath = 'cache/cachedRouteMappings.cache';

  /**
   * CachedRouteMappingsScanner constructor.
   *
   * @param \ARouter\Routing\Scanner\RouteMappingsScannerInterface $scanner
   *   Scanner which results will be cached.
   * @param string $cacheFilePath
   *   Cache file path.
   * @param bool $resetCache
   *   Resets the cache if set to true.
   */
  public function __construct(RouteMappingsScannerInterface $scanner, string $cacheFilePath = NULL, bool $resetCache = FALSE) {
    $this->scanner = $scanner;
    if (!empty($cacheFilePath)) {
      $this->cacheFilePath = $cacheFilePath;
    }
    if ($resetCache) {
      $this->clearCache();
    }
  }

  /**
   * {@inheritdoc}
   */
  public function discoverRouteMappings(): array {
    $cachedRouteMappings = $this->getRouteMappingsFromCache();
    if ($cachedRouteMappings !== NULL) {
      return $cachedRouteMappings;
    }
    else {
      $routeMappings = $this->scanner->discoverRouteMappings();
      $this->saveRouteMappingsToCache($routeMappings);
      return $routeMappings;
    }
  }

  /**
   * Clear route mappings cache.
   */
  public function clearCache(): void {
    if (file_exists($this->cacheFilePath)) {
      unlink($this->cacheFilePath);
    }
  }

  /**
   * Get cached route mappings if they exists.
   *
   * @return  \ARouter\Routing\RouteMapping[]|null
   *   Cached route mappings if they exists.
   */
  private function getRouteMappingsFromCache(): ?array {
    if (file_exists($this->cacheFilePath)) {
      $routeMappings = unserialize(file_get_contents($this->cacheFilePath));
      return $routeMappings;
    }
    return NULL;
  }

  /**
   * Save discovered route mappings to the cache.
   *
   * @param $routeMappings
   *   Route mappings for saving.
   */
  private function saveRouteMappingsToCache($routeMappings): void {
    file_put_contents($this->cacheFilePath, serialize($routeMappings));
  }

  /**
   * Get cache file path.
   *
   * @return string
   *   Cache file path.
   */
  public function getCacheFilePath(): string {
    return $this->cacheFilePath;
  }

}
