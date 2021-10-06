<?php

namespace Vinograd\FilesDriver;

use Vinograd\IO\Filesystem;
use Vinograd\Scanner\Driver;
use Vinograd\SimpleFiles\DefaultFilesystem;
use Vinograd\SimpleFiles\FileFunctionalitiesContext;

class FilesystemDriver implements Driver
{

    /** @var DefaultFilesystem */
    protected $filesystem;

    /** @var string */
    protected $detect;

    /** @var string */
    protected $next;

    public function __construct()
    {
        $this->filesystem = $this->getFilesystem();
    }

    /**
     * @return Filesystem
     */
    protected function getFilesystem(): Filesystem
    {
        return new DefaultFilesystem();
    }

    /**
     * @param string $source
     * @return array
     */
    public function parse($source): array
    {
        return $this->filesystem->scanDirectory($source);
    }

    /**
     * @param string $source
     * @return string
     */
    public function normalise($source)
    {
        return rtrim($source, DIRECTORY_SEPARATOR);
    }

    /**
     * @param string $detect
     */
    public function setDetect($detect): void
    {
        $this->detect = $detect . DIRECTORY_SEPARATOR;
    }

    /**
     * @param string $found
     * @return bool
     */
    public function isLeaf($found): bool
    {
        $this->next = $this->detect . $found;
        return !$this->filesystem->isDirectory($this->next);
    }

    /**
     * @return string
     */
    public function getDataForFilter()
    {
        return $this->next;
    }

    /**
     * @return string
     */
    public function next()
    {
        return $this->next;
    }

    /**
     *
     */
    public function beforeSearch(): void
    {
        FileFunctionalitiesContext::setFilesystem($this->filesystem);
    }

}