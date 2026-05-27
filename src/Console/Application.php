<?php

namespace Crab\Console;

use Crab\Console\Commands\EnvCheckCommand;
use Crab\Console\Commands\MakeControllerCommand;
use Crab\Console\Commands\RouteListCommand;
use Symfony\Component\Console\Application as SymfonyApplication;

class Application extends SymfonyApplication
{
    public function __construct()
    {
        parent::__construct('Crab Taoist CLI', '0.1.0');

        $this->add(new EnvCheckCommand());
        $this->add(new RouteListCommand());
        $this->add(new MakeControllerCommand());
    }
}
