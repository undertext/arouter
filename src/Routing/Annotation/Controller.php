<?php

namespace ARouter\Routing\Annotation;

/**
 * @defgroup routes_declaring Routes declaration
 *
 * ## Controller annotation
 * `Controller` annotation used to say that class with this annotation is a
 * Controller. Classes marked with this annotation will be scanned for
 * `Route` annotations.
 */

/**
 * @Annotation
 * @Target("CLASS")
 *
 * @see \ARouter\Routing\Scanner\AnnotationRouteMappingsScanner
 */
class Controller {

}
