<?php

namespace ARouter\Routing;

use ARouter\Routing\Exception\PathArgumentIsMissingException;
use Doctrine\Common\Annotations\AnnotationRegistry;
use PHPUnit\Framework\TestCase;
use ARouter\Routing\Controllers\SubDirectory\TestController2;

/**
 * Tests UrlBuilder class.
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
    AnnotationRegistry::registerLoader('class_exists');
    $this->urlBuilder = new UrlBuilder();
  }

  /**
   * @covers \ARouter\Routing\UrlBuilder
   */
  public function testFromPath() {
    $url = $this->urlBuilder->fromPath('user/{name}/{action}', [
      'name' => 'username',
      'action' => 'view',
    ]);
    self::assertEquals($url, 'user/username/view');
  }

  /**
   * @covers \ARouter\Routing\UrlBuilder
   * @covers \ARouter\Routing\Exception\PathArgumentIsMissingException
   */
  public function testExceptionFromPath() {
    try {
      $this->urlBuilder->fromPath('user/{name}/{action}', [
        'name' => 'username',
      ]);
      $this->fail();
    } catch (PathArgumentIsMissingException $ex) {
      $this->assertEquals($ex->getPath(), 'user/username/{action}');
      $this->assertEquals($ex->getArgumentName(), 'action');
    }
  }

  /**
   * @covers \ARouter\Routing\UrlBuilder
   */
  public function testFromControllerMethod() {
    $url = $this->urlBuilder->fromControllerMethod(TestController2::class, 'simpleAction2');
    self::assertEquals($url, 'simple-action-2');
  }

}
