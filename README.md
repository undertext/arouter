ARouter
========
Php annotation based router made for fun.

Quick example
-------------
```php
// Build annotaion based router, find controllers in 'src/Controller' folder.
$router = Router::build('src/Controller');
// Get matching route handler.
$routeHandler = $router->getRouteHandler(ServerRequest::fromGlobals());
if ($routeHandler) {
  $response = $routeHandler->execute();
}
else {
  $response = new Response(404, [], "Route not found"); // This is an example of Guzzle HTTP Response usage. 
}

outputResponse($response); // outputResponse is a function that converts response to string and output it.
```

Controllers
-----------
Controller is a class with *@Controller* annotation. Controller's methods annotated with
 *@Route* annotation will be registered as route handlers.
 This is an example of simple controller:
 ```php
 /**
  * @Controller
  */
 class ExampleController {
 
   /**
    * @Route(path="/example-path")
    */
   public function action() {
     print 'It works';
     }
   }
 }

 ```
 
 Arguments resolving
 ------------------
 Method arguments can be automatically resolved with help of *MethodArgumentsResolver* classes.
 
 ### Request parameter resolving

 Method argument can be resolved to GET/POST variable value using @RequestParam annotation.
  ```php
  @RequestParam (for="page")
  public function list($page) { print "Current page is $page" }
  ```
  So for request URL *some-path?page=3* $page will be resolved to *3*.
  
 ### Path argument resolving
 
 If method has placeholders in @Route path like
 ```php
  @Route (path="/user/{name}")
  public function userProfile($name){}
 ```
  then arguments named as those placeholders will be resolved to placeholder values.
  So for request URL *user/yarik* $name will be resolved to *yarik*.

 ### Request argument resolving
  
  Method argument of *RequestInterface* type like
  ```php
  public function hello(RequestInterface $r){}
  ```
  will be resolved to incoming HTTP Request object.

 ### Request body argument resolving
 
 Method argument can be resolved to request body value using @RequestBody annotation.
  ```php
  @RequestBody (for="body")
  public function something($body) { print "Request body is $body" }
  ```

 ### Request header argument resolving
 
 Method argument can be resolved to request header value using @RequestHeader annotation.
 ```php
 @RequestHeader (for="userAgent", from="User-Agent")
 public function something($userAgent) {}
 ```

 ### Session attribute argument resolving
 
 Method argument can be resolved to a session attribute value using @SessionAttribute annotation.
  ```php
  @SessionAttribute (for="some")
  public function something($some) { }
  ```
  
 ### Cookie value argument resolving
 
 Method argument can be resolved to a cookie value using @CookieValue annotation.
  ```php
  @CookieValue (for="some")
  public function something($some) { }
  ```
