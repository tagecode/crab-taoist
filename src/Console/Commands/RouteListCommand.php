<?php

namespace Crab\Console\Commands;

use Support\Routing\RouteLoader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RouteListCommand extends Command
{
    protected function configure()
    {
        $this->setName('route:list')->setDescription('List configured routes.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $routes = RouteLoader::all(BASE_PATH . '/conf/routes.php');

        foreach ($routes as $route) {
            $methods = isset($route['methods']) ? implode(',', $route['methods']) : 'ANY';
            $output->writeln(sprintf('%-16s %-8s %s', $route['name'], $methods, $route['match']));
        }

        return 0;
    }
}
