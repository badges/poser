<?php

declare(strict_types=1);

namespace PUGX\Poser\Tests\Calculator;

use PHPUnit\Framework\TestCase;
use PUGX\Poser\Calculator\SvgTextSizeCalculator;

class SvgTextSizeCalculatorTest extends TestCase
{
    private SvgTextSizeCalculator $calculator;

    protected function setUp(): void
    {
        $this->calculator = new SvgTextSizeCalculator();
    }

    public function testShouldComputeTextWidthWithSize8(): void
    {
        $width = $this->calculator->calculateWidth('MIT', 8);
        $this->assertEquals(24.1, $width);
    }

    public function testShouldComputeTextWidthWithSize10(): void
    {
        $width = $this->calculator->calculateWidth('MIT', 10);
        $this->assertEquals(27.7, $width);
    }

    public function testShouldComputeTextWidthWithSize14(): void
    {
        $width = $this->calculator->calculateWidth('MIT', 14);
        $this->assertEquals(34.8, $width);
    }

    public function testShouldComputeTextWidthWithDefaultSize(): void
    {
        $width = $this->calculator->calculateWidth('MIT');
        $this->assertIsFloat($width);
        $this->assertGreaterThan(0, $width);
    }
}
