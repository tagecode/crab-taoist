<?php

namespace Crab\Console\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class MakeControllerCommand extends Command
{
    protected function configure()
    {
        $this->setName('make:controller')
            ->setDescription('Create a Yaf API controller.')
            ->addArgument('name', InputArgument::REQUIRED, 'Controller name, for example User')
            ->addOption('module', null, InputOption::VALUE_OPTIONAL, 'Yaf module name', 'Api')
            ->addOption('api-version', null, InputOption::VALUE_OPTIONAL, 'API version directory', 'V1');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = ucfirst($input->getArgument('name'));
        $module = ucfirst($input->getOption('module'));
        $version = strtoupper($input->getOption('api-version'));

        $directory = BASE_PATH . '/application/modules/' . $module . '/controllers/' . $version;
        $path = $directory . '/' . $name . '.php';

        if (!is_dir($directory)) {
            mkdir($directory, 0775, true);
        }

        if (is_file($path)) {
            $output->writeln('Controller already exists: ' . $path);
            return 1;
        }

        $class = $version . '_' . $name . 'Controller';
        $content = $this->renderController($class);
        file_put_contents($path, $content);

        $output->writeln('Controller created: ' . $path);
        return 0;
    }

    public function renderController($class)
    {
        return "<?php\n\n"
            . "use Support\\Http\\JsonResponse;\n"
            . "use Support\\Yaf\\ControllerBase;\n\n"
            . "class " . $class . " extends ControllerBase\n"
            . "{\n"
            . "    public function indexAction()\n"
            . "    {\n"
            . "        \$this->getResponse()->clearBody();\n"
            . "        echo JsonResponse::success(array());\n"
            . "        return false;\n"
            . "    }\n"
            . "}\n";
    }
}
