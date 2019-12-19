<?php

namespace ARouter\Routing\Utility;

/**
 * Path based PHP classes detector.
 *
 * Example of finding PHP classes in 'src/Controllers' directory:
 * ```php
 *  $phpClassesDetector = new PHPClassesDetector();
 *  $classNames = $phpClassesDetector->scan('src/Controllers');
 * ```
 */
class PHPClassesDetector {

  /**
   * Detect PHP classes in directory.
   *
   * @param string $directory
   *   Directory to scan.
   *
   * @return string[]
   *   Array of founded PHP class names.
   */
  public function detect(string $directory): array {
    $classes = [];
    $directoryIterator = new \RecursiveDirectoryIterator($directory);
    $iterator = new \RecursiveIteratorIterator($directoryIterator);
    $phpFilesIterator = new \RegexIterator($iterator, '/^.+\.php$/i', \RecursiveRegexIterator::GET_MATCH);
    foreach ($phpFilesIterator as $file) {
      $file = $file[0];
      $className = $this->getFullClassName($file);
      if ($className !== NULL) {
        $classes[] = $className;
      }
    }
    return $classes;
  }

  /**
   * Get full classname of class that declared in file by filepath.
   *
   * @param string $pathToFile
   *   Path to file.
   *
   * @return null|string
   *   Full classname or NULL if no class found in file.
   */
  private function getFullClassName(string $pathToFile): ?string {
    $contents = file_get_contents($pathToFile);
    $namespace = $class = '';
    $tokens = token_get_all($contents);
    while ($token = current($tokens)) {
      if (is_array($token) && $token[0] == T_NAMESPACE) {
        while (($token = next($tokens)) && $token !== ';') {
          $namespace .= is_array($token) ? $token[1] : $token;
        }
      }
      if (is_array($token) && $token[0] == T_CLASS) {
        while ($token = next($tokens)) {
          if (is_array($token) && $token[0] == T_STRING) {
            $class = $token[1];
            break 2;
          }
        }
      }
      next($tokens);
    }
    $namespace = trim($namespace);
    return ($class === '' ? NULL : "$namespace\\$class");
  }

}
