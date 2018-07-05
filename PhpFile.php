<?php

namespace App;

class PhpFile implements PhpFileInterface
{
    /**
     * @var string
     */
    protected $file;

    /**
     * Constructor.
     *
     * @param string $pathToFile Full path to PHP file.
     */
    public function __construct(string $pathToFile)
    {
        $this->file = $pathToFile;
    }

    /**
     * {@inheritdoc}
     */
    public function read(): string
    {
        return \file_get_contents($this->file);
    }

    /**
     * {@inheritdoc}
     */
    public function write(string $content): void
    {
        \file_put_contents($this->file, $content);
    }
}
