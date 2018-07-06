<?php

namespace ARouter\Routing;

use PHPUnit\Framework\TestCase;
use ARouter\Routing\Controllers\SubDirectory\TestController2;

/**
 * Tests RoutingMiddleware functionality.
 */
class UrlBuilderTest extends TestCase {

  /**
   * URL builder.
   *
   * @var \ARouter\Routing\UrlBuilder
   */
  private $urlBuilder;

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    $this->urlBuilder = new UrlBuilder();
  }

  /**
   * Tests URL generation from path.
   */
  public function testFromPath() {
    $url = $this->urlBuilder->fromPath('user/{name}/{action}', [
      'name' => 'username',
      'action' => 'view'
    ]);
    self::assertEquals($url, 'user/username/view');
  }

  /**
   * Tests exception while URL generation from path.
   */
  public function testExceptionFromPath() {
    $this->expectException(\Exception::class);
    $this->urlBuilder->fromPath('user/{name}/{action}', [
      'name' => 'username',
    ]);
  }

  /**
   * Tests URL generation from controller and method name.
   */
  public function testFromControllerMethod() {
    $url = $this->urlBuilder->fromControllerMethod(TestController2::class, 'action2');
    self::assertEquals($url, 'testpath2');
  }

}
