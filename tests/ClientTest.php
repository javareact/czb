<?php

declare(strict_types=1);

namespace Test\CzbApi;

use PHPUnit\Framework\TestCase;
use ReflectionClass;
Use ReflectionMethod;
use Test\CzbApi\Stub\Client;

/**
 * Class ClientTest
 * @package Test\CzbApi
 * @internal
 * @covers \JavaReact\CzbApi\Client
 */
class ClientTest extends TestCase
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var ReflectionMethod
     */
    private $method;

    protected function setUp(): void
    {
        $this->client    = new Client();//fixme
        $reflectionClass = new ReflectionClass(Client::class);
        $method          = $reflectionClass->getMethod("resolveOptions");
        $method->setAccessible(true);
        $this->method = $method;
    }

    public function testResolveOptions()
    {
        $options = [
            "foo"   => "bar",
            "hello" => "world",
            "php"   => "test",
        ];

        $availableOptions = [
            "foo", "php",
        ];

        $result = $this->method->invoke($this->client, $options, $availableOptions);

        $excepted = [
            "foo" => "bar",
            "php" => "test",
        ];

        $this->assertSame($excepted, $result);
    }

    public function testResolveOptionsWithoutMatch()
    {
        $options = [
            "foo" => "bar",
        ];

        $availableOptions = [
            "hello", "php",
        ];

        $result = $this->method->invoke($this->client, $options, $availableOptions);

        $this->assertSame([], $result);
    }
}