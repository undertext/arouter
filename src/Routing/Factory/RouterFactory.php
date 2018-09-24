<?php

namespace ARouter\Routing\Factory;

use ARouter\Routing\HttpMessageConverter\HttpMessageConverterManager;
use ARouter\Routing\Resolver\CookieValueArgumentResolver;
use ARouter\Routing\Resolver\PathArgumentResolver;
use ARouter\Routing\Resolver\RequestArgumentResolver;
use ARouter\Routing\Resolver\RequestBodyArgumentResolver;
use ARouter\Routing\Resolver\RequestHeaderArgumentResolver;
use ARouter\Routing\Resolver\RequestParamArgumentResolver;
use ARouter\Routing\Resolver\Service\MethodArgumentsResolverService;
use ARouter\Routing\Resolver\SessionAttributeArgumentResolver;
use ARouter\Routing\Router;
use ARouter\Routing\Scanner\AnnotationRouteMappingsScanner;
use ARouter\Routing\Scanner\CachedRouteMappingsScanner;
use ARouter\Routing\Utility\PHPClassesDetector;
use Doctrine\Common\Annotations\AnnotationReader;

/**
 * @defgroup router_creation Creating the router
 * @{
 * The easiest way of getting router object is to use `RouterFactory`
 * class.
 *
 * ## Getting simple router
 * To get a simple annotation based router just call
 * ```php
 * $router = RouterFactory::getRouter('directory')
 * ```
 * and pass the path to the controllers directory as the first parameter.
 * You can also provide custom HTTP message converters and argument resolvers
 * as 2nd and 3rd arguments.
 *
 * ## Getting cached router
 * To get a cached annotation based router just call
 *
 * ```php
 * $router = RouterFactory::getCachedRouter('directory',
 *   'cache/cacheFile.cache')
 * ```
 * and pass the path to the controllers directory as the first parameter and
 * optionally cache file name as a second argument.
 *
 * You can also provide custom HTTP message converters and argument resolvers
 * as 3rd and 4th arguments.
 *
 * ### Cache clear
 * In order to clear the route mappings cache call
 * ```php
 * $router->getScanner()->clearCache();
 * ```
 * @}
 */

/**
 * Used to construct and return different kind of routers.
 */
class RouterFactory {

  /**
   * Get simple annotation based router.
   *
   * Created router has all provided by this library argument resolvers,
   * and use provided by this library Route mappings scanner.
   *
   * @param string $controllersDirectory
   *   Directory where controllers are stored.
   * @param \ARouter\Routing\HttpMessageConverter\HttpMessageConverterInterface[] $converters
   *   Additional HTTP message converters.
   * @param \ARouter\Routing\Resolver\MethodArgumentResolver[] $resolvers
   *   Additional argument resolvers.
   *
   * @return \ARouter\Routing\Router
   *   Simple annotation based router.
   */
  public static function getRouter(string $controllersDirectory, array $converters = [], array $resolvers = []): Router {
    $resolverService = self::getArgumentResolverService($resolvers);
    $converterManager = self::getConverterManager($converters);
    $scanner = new AnnotationRouteMappingsScanner($controllersDirectory, new AnnotationReader(), new PHPClassesDetector());
    return new Router($scanner, $resolverService, $converterManager);
  }

  /**
   * Get cached annotation based router.
   *
   * The difference between this router and simple router is that cached router
   * uses cached Route mappings scanner.
   *
   * @param string $controllersDirectory
   *   Directory where controllers are stored.
   * @param string $cacheFilePath
   *   Cache file path.
   * @param \ARouter\Routing\HttpMessageConverter\HttpMessageConverterInterface[] $converters
   *   Additional HTTP message converters.
   * @param \ARouter\Routing\Resolver\MethodArgumentResolver[] $resolvers
   *   Additional argument resolvers.
   *
   * @return \ARouter\Routing\Router
   *   Annotation based router.
   */
  public static function getCachedRouter(string $controllersDirectory, string $cacheFilePath = NULL, array $converters = [], array $resolvers = []): Router {
    $resolverService = self::getArgumentResolverService($resolvers);
    $converterManager = self::getConverterManager($converters);
    $scanner = new AnnotationRouteMappingsScanner($controllersDirectory, new AnnotationReader(), new PHPClassesDetector());
    $scanner = new CachedRouteMappingsScanner($scanner, $cacheFilePath);
    return new Router($scanner, $resolverService, $converterManager);
  }

  /**
   * Get argument resolver service with populated default resolvers.
   *
   * @param \ARouter\Routing\Resolver\MethodArgumentResolver[] $resolvers
   *   Additional argument resolvers to pass to resolvers service.
   *
   * @return \ARouter\Routing\Resolver\Service\MethodArgumentsResolverService
   */
  private static function getArgumentResolverService(array $resolvers): MethodArgumentsResolverService {
    $argumentsResolverService = new MethodArgumentsResolverService();
    $argumentsResolverService->addArgumentResolvers([
      new RequestArgumentResolver(),
      new RequestParamArgumentResolver(),
      new PathArgumentResolver(),
      new RequestBodyArgumentResolver(),
      new CookieValueArgumentResolver(),
      new RequestHeaderArgumentResolver(),
      new SessionAttributeArgumentResolver(),
    ]);
    if (!empty($resolvers)) {
      $argumentsResolverService->addArgumentResolvers($resolvers);
    }
    return $argumentsResolverService;
  }

  /**
   * Get converter manager object.
   *
   * @param \ARouter\Routing\HttpMessageConverter\HttpMessageConverterInterface[]
   *   Converters to pass to converter manager.
   *
   * @return \ARouter\Routing\HttpMessageConverter\HttpMessageConverterManager
   */
  private static function getConverterManager(array $converters): HttpMessageConverterManager {
    $converterManager = new HttpMessageConverterManager();
    if (!empty($converters)) {
      $converterManager->addConverters($converters);
    }
    return $converterManager;
  }

}
