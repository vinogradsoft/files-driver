<?php
declare(strict_types=1);

namespace Test;

use Test\Cases\Dummy\DummyNodeFactory;
use Test\Cases\IoEnvCase;
use Vinograd\FilesDriver\FilesystemDriver;
use Vinograd\SimpleFiles\DefaultFilesystem;
use Vinograd\SimpleFiles\FileFunctionalitiesContext;

class FilesystemDriverTest extends IoEnvCase
{

    public function testIsLeaf()
    {
        $arrayDriver = new FilesystemDriver();
        self::assertTrue($arrayDriver->isLeaf(__FILE__));
        self::assertFalse($arrayDriver->isLeaf(__DIR__));
    }

    public function testIsLeafWithSnap()
    {
        $arrayDriver = new FilesystemDriver();
        $arrayDriver->isLeaf(__DIR__);
        $reflection = new \ReflectionObject($arrayDriver);
        $property = $reflection->getProperty('next');
        $property->setAccessible(true);
        $objectValue = $property->getValue($arrayDriver);
        self::assertEquals($objectValue, __DIR__);
    }

    public function testNormalise()
    {
        $arrayDriver = new FilesystemDriver();
        $result = $arrayDriver->normalise(__DIR__ . '/');
        self::assertEquals(__DIR__, $result);
    }

    public function testGetDataFotFilter()
    {
        $arrayDriver = new FilesystemDriver();
        $arrayDriver->isLeaf(__FILE__);
        self::assertEquals($arrayDriver->getDataForFilter(), __FILE__);
        $arrayDriver->isLeaf(__DIR__);
        self::assertEquals($arrayDriver->getDataForFilter(), __DIR__);
    }

    public function testParse()
    {
        $arrayDriver = new FilesystemDriver();
        $this->createFilesystem([
            'directories' => [
                $this->outPath . '/childL',
                $this->outPath . '/childL/root',
            ],
            'files' => [
                $this->outPath . '/childL/file1.txt' => '',
                $this->outPath . '/childL/file2.txt' => '',
            ],
        ]);
        $control = [
            'file1.txt',
            'file2.txt',
            'root',
        ];
        $result = $arrayDriver->parse($this->outPath . '/childL');

        self::assertEquals($control, $result);
    }

    public function testBeforeSearch()
    {
        $driver = new FilesystemDriver();
        $driver->beforeSearch();
        $support = FileFunctionalitiesContext::getGroupFunctionalitySupport('group1');
        $fs1 = $support->getFilesystem()->extractFilesystem();
        self::assertInstanceOf(DefaultFilesystem::class, $fs1);
    }

    public function testSetDetect()
    {
        $driver = new FilesystemDriver();
        $driver->setDetect($this->outPath);
        $reflection = new \ReflectionObject($driver);
        $property = $reflection->getProperty('detect');
        $property->setAccessible(true);
        $objectValue = $property->getValue($driver);
        self::assertEquals($this->outPath . '/', $objectValue);
    }

    public function testNext()
    {
        $this->createFile($file = $this->outPath . '/fileTest.txt');
        $driver = new FilesystemDriver();
        $driver->setDetect($this->outPath);
        $driver->isLeaf('fileTest.txt');
        $result = $driver->next();
        self::assertEquals($this->outPath . '/fileTest.txt', $result);
    }
}
