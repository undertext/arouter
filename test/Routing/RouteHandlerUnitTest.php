<?php

namespace ARouter\Routing;

use PHPUnit\Framework\TestCase;
use ARouter\Routing\Controllers\TestController;
use ARouter\Routing\Exception\MissingArgumentHandlerException;

/**
 * Tests RouteHandler class.
 */
class RouteHandlerUnitTest extends TestCase {

  /**
   * @covers \ARouter\Routing\RouteHandler
   */
  public function testCreation(){
    $controller = new TestController();
    $routeHandler = new RouteHandler($controller, 'simpleAction', ['a' => 'b']);

    $this->assertEquals($routeHandler->getController(), $controller);
    $this->assertEquals($routeHandler->getMethod(), 'simpleAction');
    $this->assertEquals($routeHandler->getKeyedArgs(), ['a' => 'b']);

  }

  /**
   * @covers \ARouter\Routing\RouteHandler::convertKeyedArgs()
   */
  public function testConvertKeyedArgs() {
    $controller = new TestController();
    $routeHandler = new RouteHandler($controller, 'queryParamsAction');
    $convertedArgs = $routeHandler->convertKeyedArgs([
      'arg1' => 'testarg1',
      'arg2' => 'testarg2',
    ]);
    self::assertEquals($convertedArgs, [
      'testarg1',
      'testarg2',
      'default',
    ]);
  }

  /**
   * @covers \ARouter\Routing\RouteHandler::convertKeyedArgs()
   * @covers \ARouter\Routing\Exception\MissingArgumentHandlerException
   */
  public function testConvertKeyedArgsException() {
    $controller = new TestController();
    $routeHandler = new RouteHandler($controller, 'queryParamsAction');
    try {
      $routeHandler->convertKeyedArgs([
        'arg1' => 'testarg1',
      ]);
      $this->fail();
    } catch (MissingArgumentHandlerException $ex) {
      $this->assertEquals($ex->getController(), $controller);
      $this->assertEquals($ex->getMethodName(), 'queryParamsAction');
      $this->assertEquals($ex->getParameterName(), 'arg2');
    }
  }

  /**
   * @covers \ARouter\Routing\RouteHandler::execute()
   */
  public function testExecute() {
    $controller = new TestController();
    $routeHandler = new RouteHandler($controller, 'parameterInPathAction', ['name' => 'testname']);
    $result = $routeHandler->execute();
    self::assertEquals($result->getBody(), "Given parameter is testname");
  }

}
