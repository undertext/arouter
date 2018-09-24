<?php

namespace ARouter\Routing\Scanner;

/**
 * Interface for classes that want to provide route mappings for router.
 */
interface RouteMappingsScannerInterface {

  /**
   * Discover and return route mappings.
   *
   * @return \ARouter\Routing\RouteMapping[]
   *   Route mappings.
   */
  public function discoverRouteMappings(): array;
}
