ARouter
========
Php annotation based router made for fun.
Documentation can be found [here](https://undertext.github.io/arouter/html/index.html)

Quick example
-------------
```php
// Enable annotations autolading. This is a reuired step.
AnnotationRegistry::registerLoader('class_exists');
// Build annotaion based router, find controllers in 'src/Controller' folder.
$router = RouterFactory::getRouter('src/Controller');
// Get response based on request.
try {
   $response = $router->getResponse(ServerRequest::fromGlobals());
} catch (RouteHandlerNotFoundException $e) {
    $response = new Response(404, [], "Route not found"); // This is an example of Guzzle HTTP Response usage.
}

outputResponse($response); // outputResponse is a function that converts response to string and output it.
```

Main documentation sections:
----------------------------

*NOTE: Due to Doxygen parser all code examples with PHP annotations do not wrap those annotations in DocBlock comment*

- [Routes declaration](https://undertext.github.io/arouter/html/group__routes__declaring.html)
- [Creating the router](https://undertext.github.io/arouter/html/group__router__creation.html)
- [HTTP Message Converters](https://undertext.github.io/arouter/html/group__http__message__converters.html)
- [Argument resolvers](https://undertext.github.io/arouter/html/group__argument__resolvers.html)
- [PSR-15 MIddleware](https://undertext.github.io/arouter/html/group__middleware.html)
- [Exceptions](https://undertext.github.io/arouter/html/group__exceptions.html)

[![Build Status](https://travis-ci.com/undertext/arouter.svg?branch=master)](https://travis-ci.com/undertext/arouter)
[![codecov](https://codecov.io/gh/undertext/arouter/branch/master/graph/badge.svg)](https://codecov.io/gh/undertext/arouter)
