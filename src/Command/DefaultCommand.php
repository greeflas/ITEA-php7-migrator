<?php

namespace Greeflas\SyntaxMigrator\Command;

use  Greeflas\SyntaxMigrator\PhpFile;
use  Greeflas\SyntaxMigrator\PhpFileInfo;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\{
    InputArgument, InputInterface
};
use Symfony\Component\Console\Output\OutputInterface;

class DefaultCommand extends Command
{
    /**
     * {@inheritdoc}
     */
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
            ->addArgument(
                'class-name',
                InputArgument::REQUIRED,
                'Class name'
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $pathToFile = $input->getArgument('path-to-file');

        $output->writeln('<info>Start migration...</info>');

        $phpFile = new PhpFile($pathToFile);
        $phpFileContent = $phpFile->read();

        $className = $input->getArgument('class-name');
        $fileInfo = new PhpFileInfo($className);

        foreach ($fileInfo->getMethods() as $method) {
            $docBlock = $fileInfo->getDocBlock($method);
            $signature = $fileInfo->getMethodSignature($method, $phpFileContent);
            $oldSignature = $signature;

            foreach ($docBlock->getTagsByName('param') as $param) {
                $type = (string) $param->getType();
                $types = \explode('|', $type);
                $paramName = '$' . $param->getVariableName();
                $typesNum = \count($types);

                if ('mixed' === $type) {
                    continue;
                }

                if ($typesNum > 2) {
                    continue;
                } elseif ($typesNum === 2 && \in_array('null', $types)) {
                    $type = '?' . ($types[0] === 'null' ? $types[1] : $types[0]);
                }

                $signature = \str_replace($paramName, $type . ' ' . $paramName, $signature);
            }

            $returns = $docBlock->getTagsByName('return');

            if (\count($returns) > 1) {
                throw new \RuntimeException('Tag @return cannot be declared multiple times');
            }

            $return = \array_shift($returns);
            $type = (string) $return->getType();

            // TODO: check for mixed, multiple types, etc.

            $signature .= ': ' . $type;

            $phpFileContent = \str_replace($oldSignature, $signature, $phpFileContent);
        }

        $phpFile->write($phpFileContent);
    }
}
