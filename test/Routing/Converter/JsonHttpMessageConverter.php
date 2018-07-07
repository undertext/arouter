<?php

namespace ARouter\Routing\Converter;

use ARouter\Routing\HttpMessageConverter\HttpMessageConverterInterface;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;

class JsonHttpMessageConverter implements HttpMessageConverterInterface {

  /**
   * @var \Symfony\Component\Serializer\Serializer
   */
  private $serializer;


  public function getFormat(): string {
    return 'application/json';
  }

  public function __construct() {
    $encoder = new JsonEncoder();
    $normalizer = new GetSetMethodNormalizer();
    $this->serializer = new Serializer([$normalizer], [$encoder]);
  }

  public function toResponse($object): ResponseInterface {
    return new Response(200, [], $this->serializer->serialize($object, 'json'));
  }

}
