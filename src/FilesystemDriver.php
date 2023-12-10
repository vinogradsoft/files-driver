<?php
declare(strict_types=1);

namespace Vinograd\FilesDriver;

use Vinograd\IO\Exception\IOException;
use Vinograd\Scanner\Driver;

class FilesystemDriver implements Driver
{

    protected string|null $detect = null;
    protected string $next;

    /**
     * @param string $source
     * @return array
     */
    public function parse($source): array
    {
        $result = @scandir($source);
        if (is_bool($result)) {
            throw new IOException(sprintf('Invalid path: %s', $source));
        }
        return array_slice($result, 2);
    }

    /**
     * @param string $source
     * @return string
     */
    public function normalize(mixed $source): string
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
    public function getDataForFilter(): string
    {
        return $this->next;
    }

    /**
     * @return string
     */
    public function next(): string
    {
        return $this->next;
    }

    /**
     * @return void
     */
    public function beforeSearch(): void
    {

    }

}