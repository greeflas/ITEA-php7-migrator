<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DefaultCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('migrate')
            ->setDescription('Migrate old PHP 5 syntax to new PHP 7.x syntax')
            ->setHelp('Run command ./migrator migrate \<path-to-file>')
            ->addArgument(
                'path-to-file',
                InputArgument::REQUIRED,
                'Path to class to migrate'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $pathToFile = $input->getArgument('path-to-file');

        $output->writeln('<info>Start migration...</info>');
        $output->writeln($pathToFile);
    }
}
