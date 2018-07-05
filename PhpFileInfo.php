<?php

namespace App;

use phpDocumentor\Reflection\DocBlock;
use phpDocumentor\Reflection\DocBlockFactory;

class PhpFileInfo
{
    protected $reflector;
    protected $docBlockFactory;

    public function __construct(string $className)
    {
        $this->reflector = new \ReflectionClass($className);
        $this->docBlockFactory = DocBlockFactory::createInstance();
    }

    public function getMethods(): array
    {
        return $this->reflector->getMethods();
    }

    public function getDocBlock(\ReflectionMethod $method): DocBlock
    {
        return $this->docBlockFactory->create($method);
    }

    public function getMethodSignature(\ReflectionMethod $method, string $classContent): string
    {
        $pattern = '/' . $method->getName() . '\(.+\)' . '/';
        \preg_match($pattern, $classContent, $matches);

        return $matches[0];
    }
}
