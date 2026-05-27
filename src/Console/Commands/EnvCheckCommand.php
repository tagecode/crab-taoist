<?php

namespace Crab\Console\Commands;

use Support\Yaf\Compat;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class EnvCheckCommand extends Command
{
    protected function configure()
    {
        $this->setName('env:check')->setDescription('Check Crab Taoist runtime requirements.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $failed = 0;
        $checks = array(
            'PHP >= 7.0' => version_compare(PHP_VERSION, '7.0.0', '>='),
            'Yaf extension' => extension_loaded('yaf'),
            'Yaf class mode (0 or 1)' => Compat::isSupported(),
            'JSON extension' => extension_loaded('json'),
            'OpenSSL extension' => extension_loaded('openssl'),
            'Composer autoload' => is_file(BASE_PATH . '/vendor/autoload.php'),
            '.env file' => is_file(BASE_PATH . '/.env'),
            'runtime/logs writable' => is_writable(BASE_PATH . '/runtime/logs'),
            'runtime/cache writable' => is_writable(BASE_PATH . '/runtime/cache'),
        );

        foreach ($checks as $name => $passed) {
            $output->writeln(sprintf('%s %s', $passed ? '[OK]' : '[FAIL]', $name));
            if (!$passed) {
                $failed++;
            }
        }

        if ($failed > 0) {
            $output->writeln('Environment check failed. Please fix failed items before running the application.');
            return 1;
        }

        $output->writeln('Environment check passed.');
        $output->writeln('Yaf mode: ' . Compat::modeLabel());
        return 0;
    }
}
