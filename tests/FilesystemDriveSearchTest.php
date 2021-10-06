<?php

namespace Test;

use Test\Cases\Dummy\DummyNodeFactory;
use Test\Cases\Dummy\TestCaseProviderVisitor;
use Test\Cases\StrategyCase;
use Vinograd\FilesDriver\FilesystemDriver;
use Vinograd\Scanner\AbstractTraversalStrategy;
use Vinograd\Scanner\BreadthStrategy;
use Vinograd\Scanner\NodeFactory;
use Vinograd\Scanner\Scanner;

class FilesystemDriveSearchTest extends StrategyCase
{
    private $strategy;
    private $driver;
    private $factory;

    private $leafCounter = 0;
    private $nodeCounter = 0;
    private $nodeLog = [];
    private $leafLog = [];
    private $visitor;

    public function setUp(): void
    {
        parent::setUp();
        $this->visitor = new TestCaseProviderVisitor($this);
        $this->strategy = new BreadthStrategy();;
        $this->factory = new DummyNodeFactory();
        $this->driver = new FilesystemDriver();
        $this->createFilesystem([
            'directories' => [
                $this->outPath . '/childL',
                $this->outPath . '/childL/root',
                $this->outPath . '/childL/root/child1',
                $this->outPath . '/childL/root/child1/child2',
                $this->outPath . '/childL/root/child1/child2/child3',
                $this->outPath . '/childL/root/child1/child2/child3/child4',
                $this->outPath . '/childL/root/child1/child2/child3/child4/child5',
            ],
            'files' => [
                $this->outPath . '/childL/file1.txt' => 'initial1',
                $this->outPath . '/childL/root/file7.txt' => 'initial7',
                $this->outPath . '/childL/root/child1/file6.txt' => 'initial6',
                $this->outPath . '/childL/root/child1/child2/file5.txt' => 'initial5',
                $this->outPath . '/childL/root/child1/child2/child3/file4.txt' => 'initial4',
                $this->outPath . '/childL/root/child1/child2/child3/child4/file3.txt' => 'initial3',
                $this->outPath . '/childL/root/child1/child2/child3/child4/child5/file2.txt' => 'initial2',
            ],
        ]);
    }

    /**
     * @dataProvider getCase
     */
    public function testSearch( $expectedFiles, $expectedDirectories)
    {
        $scanner = new Scanner();
        $scanner->setStrategy($this->strategy);
        $scanner->setVisitor($this->visitor);
        $scanner->setDriver($this->driver);
        $scanner->setNodeFactory($this->factory);

        $scanner->search($this->outPath);

        self::assertCount($this->leafCounter, $expectedFiles);
        self::assertCount($this->nodeCounter, $expectedDirectories);

        self::assertEquals($expectedFiles, $this->leafLog);
        self::assertEquals($expectedDirectories, $this->nodeLog);
    }

    public function getCase()
    {

        return [
            [//line1
                [//files
                    'file1.txt',
                    'file7.txt',
                    'file6.txt',
                    'file5.txt',
                    'file4.txt',
                    'file3.txt',
                    'file2.txt',
                ],
                [//directories
                    'childL',
                    'root',
                    'child1',
                    'child2',
                    'child3',
                    'child4',
                    'child5',
                ]
            ],
        ];
    }

    public function scanStarted(AbstractTraversalStrategy $scanStrategy, $detect): void
    {

    }

    public function scanCompleted(AbstractTraversalStrategy $scanStrategy, NodeFactory $factory, $detect): void
    {

    }

    public function visitLeaf(AbstractTraversalStrategy $scanStrategy, NodeFactory $factory, $detect, $found, $data = null): void
    {
        $this->leafCounter++;
        $this->leafLog [] = $found;
    }

    public function visitNode(AbstractTraversalStrategy $scanStrategy, NodeFactory $factory, $detect, $found, $data = null): void
    {
        $this->nodeCounter++;
        $this->nodeLog[] = $found;
    }

    public function tearDown(): void
    {
        $this->strategy = null;
        $this->driver = null;
        $this->factory = null;

        $this->leafCounter = 0;
        $this->nodeCounter = 0;
        $this->nodeLog = [];
        $this->leafLog = [];
        parent::tearDown();
    }
}