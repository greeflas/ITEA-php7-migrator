<?php

namespace Greeflas\SyntaxMigrator;

/**
 * Interface for PHP file.
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 * @since 1.0
 */
interface PhpFileInterface
{
    /**
     * Reads PHP file.
     *
     * @return string Content of PHP file.
     */
    public function read(): string;

    /**
     * Writes content to the PHP file.
     *
     * @param string $content New content for PHP file.
     */
    public function write(string $content): void;
}
