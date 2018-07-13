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
   */
  public function testConvertKeyedArgsException() {
    $controller = new TestController();
    $routeHandler = new RouteHandler($controller, 'action');
    try {
      $routeHandler->convertKeyedArgs([
        'arg2' => 'testarg2',
      ]);
      $this->fail();
    } catch (MissingArgumentHandlerException $ex) {
      $this->assertEquals($ex->getController(), $controller);
      $this->assertEquals($ex->getMethodName(), 'action');
      $this->assertEquals($ex->getParameterName(), 'name');
    }
  }

  /**
   * Tests execute method.
   */
  public function testExecute() {
    $controller = new TestController();
    $routeHandler = new RouteHandler($controller, 'action', ['name' => 'testname']);
    $result = $routeHandler->execute();
    self::assertEquals($result, "Some response");
  }

}
