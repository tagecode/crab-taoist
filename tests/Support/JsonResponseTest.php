<?php

namespace Tests\Support;

use PHPUnit\Framework\TestCase;
use Support\Http\JsonResponse;

class JsonResponseTest extends TestCase
{
    public function testSuccessResponseContainsStandardFields()
    {
        $json = JsonResponse::success(array('pong' => true));
        $data = json_decode($json, true);

        $this->assertSame(200, $data['code']);
        $this->assertSame('success', $data['message']);
        $this->assertSame(array('pong' => true), $data['data']);
        $this->assertArrayHasKey('request_id', $data);
    }

    public function testErrorResponseContainsMessageAndCode()
    {
        $json = JsonResponse::error('失败', 400, array(), 400);
        $data = json_decode($json, true);

        $this->assertSame(400, $data['code']);
        $this->assertSame('失败', $data['message']);
    }
}
