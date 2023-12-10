<?php
declare(strict_types=1);

namespace Test\Cases\Dummy;

use Test\Cases\StrategyCase;
use Vinograd\Scanner\AbstractTraversalStrategy;
use Vinograd\Scanner\Visitor;

class TestCaseProviderVisitor implements Visitor
{
    /** @var StrategyCase */
    protected $testCase;

    /**
     * @param StrategyCase $testCase
     */
    public function __construct(StrategyCase $testCase)
    {
        $this->testCase = $testCase;
    }

    /**
     * @param AbstractTraversalStrategy $scanStrategy
     * @param $detect
     */
    public function scanStarted(AbstractTraversalStrategy $scanStrategy, $detect): void
    {
        $this->testCase->scanStarted($scanStrategy, $detect);
    }

    /**
     * @param AbstractTraversalStrategy $scanStrategy
     * @param $detect
     * @return void
     */
    public function scanCompleted(AbstractTraversalStrategy $scanStrategy, $detect): void
    {
        $this->testCase->scanCompleted($scanStrategy, $detect);
    }

    /**
     * @param AbstractTraversalStrategy $scanStrategy
     * @param mixed $parentNode
     * @param mixed $currentElement
     * @param mixed|null $data
     * @return void
     */
    public function visitLeaf(AbstractTraversalStrategy $scanStrategy, mixed $parentNode, mixed $currentElement, mixed $data = null): void
    {
        $this->testCase->visitLeaf($scanStrategy, $parentNode, $currentElement, $data);
    }

    /**
     * @param AbstractTraversalStrategy $scanStrategy
     * @param mixed $parentNode
     * @param mixed $currentNode
     * @param mixed|null $data
     * @return void
     */
    public function visitNode(AbstractTraversalStrategy $scanStrategy, mixed $parentNode, mixed $currentNode, mixed $data = null): void
    {
        $this->testCase->visitNode($scanStrategy, $parentNode, $currentNode, $data);
    }

    public function equals(Visitor $visitor): bool
    {
        return $this === $visitor;
    }
}