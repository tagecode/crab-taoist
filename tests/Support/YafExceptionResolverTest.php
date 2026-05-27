<?php

namespace Tests\Support;

use PHPUnit\Framework\TestCase;
use Support\Exceptions\YafExceptionResolver;

class YafExceptionResolverTest extends TestCase
{
    public function testDetectsControllerLoadFailedByYafClassName()
    {
        if (class_exists('Yaf\\Exception\\LoadFailed\\Controller')) {
            $exception = new \Yaf\Exception\LoadFailed\Controller('controller not found');
        } elseif (class_exists('Yaf_Exception_LoadFailed_Controller')) {
            $exception = new \Yaf_Exception_LoadFailed_Controller('controller not found');
        } else {
            $this->markTestSkipped('Yaf LoadFailed\\Controller not available in this environment.');
        }

        $this->assertTrue(YafExceptionResolver::isNotFound($exception));
    }

    public function testDetectsControllerLoadFailedByMessage()
    {
        $exception = new \Exception('Failed opening controller script /app/controllers/Foo.php');

        $this->assertTrue(YafExceptionResolver::isNotFound($exception));
    }

    public function testResolveNotFoundReturns404Payload()
    {
        $exception = new \Exception('Failed opening controller script /app/controllers/Foo.php');
        $resolved = YafExceptionResolver::resolve($exception, true);

        $this->assertSame('Not Found', $resolved['message']);
        $this->assertSame(404, $resolved['code']);
        $this->assertSame(404, $resolved['httpStatus']);
        $this->assertSame(array(), $resolved['data']);
        $this->assertSame('info', $resolved['logLevel']);
    }

    public function testResolveGenericErrorHidesDetailsWhenDebugOff()
    {
        $exception = new \Exception('boom');
        $resolved = YafExceptionResolver::resolve($exception, false);

        $this->assertSame('服务器内部错误', $resolved['message']);
        $this->assertSame(500, $resolved['httpStatus']);
        $this->assertSame(array(), $resolved['data']);
    }
}
