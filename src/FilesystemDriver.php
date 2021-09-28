<?php

namespace Vinograd\FilesDriver;

use Vinograd\Scanner\Driver;
use Vinograd\Scanner\NodeFactory;
use Vinograd\SimpleFiles\DefaultFilesystem;
use Vinograd\SimpleFiles\FileFunctionalitiesContext;

class FilesystemDriver implements Driver
{
    /**  @var NodeFactory */
    protected $nodeFactory;

    /** @var DefaultFilesystem */
    protected $filesystem;

    /** @var string */
    protected $detect;

    /** @var string */
    protected $next;

    /**
     * @param NodeFactory $factory
     */
    public function __construct(NodeFactory $factory)
    {
        $this->nodeFactory = $factory;
        $this->filesystem = new DefaultFilesystem();
    }

    /**
     * @return NodeFactory
     */
    public function getNodeFactory(): NodeFactory
    {
        return $this->nodeFactory;
    }

    /**
     * @param string $source
     * @return array
     */
    public function parse($source): array
    {
        return array_slice(scandir($source), 2);
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
        return !is_dir($this->next);
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