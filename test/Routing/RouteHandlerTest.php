<?php

namespace ARouter\Routing;

use PHPUnit\Framework\TestCase;
use ARouter\Routing\Controllers\TestController;
use ARouter\Routing\Exception\MissingArgumentHandlerException;

/**
 * Tests RouteHandler class.
 */
class RouteHandlerTest extends TestCase {

  /**
   * Tests convertKeyedArgs method.
   */
  public function testConvertKeyedArgs() {
    $controller = new TestController();
    $routeHandler = new RouteHandler($controller, 'action');
    $convertedArgs = $routeHandler->convertKeyedArgs([
      'name' => 'testname',
      'arg' => 'testarg1',
    ]);
    self::assertEquals($convertedArgs, [
      'testname',
      NULL,
      NULL,
      'testarg1',
      'defaultarg2',
    ]);

    $convertedArgs = $routeHandler->convertKeyedArgs([
      'arg2' => 'testarg2',
      'name' => 'testname',
    ]);
    self::assertEquals($convertedArgs, [
      'testname',
      NULL,
      NULL,
      NULL,
      'testarg2',
    ]);
  }

  /**
   * Tests exception in convertKeyedArgs method.
   *
   * @covers \ARouter\Routing\RouteHandler::convertKeyedArgs()
   */
  public function testConvertKeyedArgsException() {
    self::expectException(MissingArgumentHandlerException::class);
    $controller = new TestController();
    $routeHandler = new RouteHandler($controller, 'action');
    $routeHandler->convertKeyedArgs([
      'arg2' => 'testarg2',
    ]);
  }

  /**
   * Tests execute method.
   */
  public function testExecute() {
    $controller = new TestController();
    $routeHandler = new RouteHandler($controller, 'action', ['name' => 'testname']);
    $response = $routeHandler->execute();
    self::assertEquals($response->getBody(), "Test controller body");
  }

}
