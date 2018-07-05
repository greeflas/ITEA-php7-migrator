<?php

namespace Greeflas\SyntaxMigrator;

use phpDocumentor\Reflection\DocBlock;
use phpDocumentor\Reflection\DocBlockFactory;

/**
 * Provides some info about PHP class.
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 * @since 1.0
 */
class PhpFileInfo
{
    /**
     * @var \ReflectionClass
     */
    protected $reflector;

    /**
     * @var DocBlockFactory
     */
    protected $docBlockFactory;

    /**
     * Constructor.
     *
     * @param string $className
     *
     * @throws \ReflectionException
     */
    public function __construct(string $className)
    {
        $this->reflector = new \ReflectionClass($className);
        $this->docBlockFactory = DocBlockFactory::createInstance();
    }

    /**
     * Gets class methods list.
     *
     * @return \ReflectionMethod[]
     */
    public function getMethods(): array
    {
        return $this->reflector->getMethods();
    }

    /**
     * Gets DocBlock for method.
     *
     * @param \ReflectionMethod $method
     *
     * @return DocBlock
     */
    public function getDocBlock(\ReflectionMethod $method): DocBlock
    {
        return $this->docBlockFactory->create($method);
    }

    /**
     * Gets method signature.
     *
     * @param \ReflectionMethod $method
     * @param string            $classContent
     *
     * @return string
     */
    public function getMethodSignature(\ReflectionMethod $method, string $classContent): string
    {
        $pattern = '/' . $method->getName() . '\(.+\)' . '/';
        \preg_match($pattern, $classContent, $matches);

        return $matches[0];
    }
}
