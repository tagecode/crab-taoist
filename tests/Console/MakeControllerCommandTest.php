<?php

namespace Tests\Console;

use Crab\Console\Commands\MakeControllerCommand;
use PHPUnit\Framework\TestCase;

class MakeControllerCommandTest extends TestCase
{
    public function testRenderControllerCreatesYafControllerClass()
    {
        $command = new MakeControllerCommand();
        $content = $command->renderController('V1_UserController');

        $this->assertContains('class V1_UserController extends ControllerBase', $content);
        $this->assertContains('use Support\\Yaf\\ControllerBase', $content);
        $this->assertContains('JsonResponse::success', $content);
        $this->assertContains('indexAction', $content);
    }
}
