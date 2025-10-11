<?php

declare(strict_types=1);

namespace PUGX\Poser\Tests\Calculator;

use PHPUnit\Framework\TestCase;
use PUGX\Poser\Calculator\GDTextSizeCalculator;

class GDTextSizeCalculatorTest extends TestCase
{
    private GDTextSizeCalculator $calculator;

    protected function setUp(): void
    {
        $this->calculator = new GDTextSizeCalculator();
    }

    public function testShouldComputeTextWidthWithSize8(): void
    {
        $width = $this->calculator->calculateWidth('MIT', 8);
        $this->assertEquals(24.0, $width);
    }

    public function testShouldComputeTextWidthWithSize10(): void
    {
        $width = $this->calculator->calculateWidth('MIT', 10);
        $this->assertEquals(29.0, $width);
    }

    public function testShouldComputeTextWidthWithSize14(): void
    {
        $width = $this->calculator->calculateWidth('MIT', 14);
        $this->assertEquals(34.0, $width);
    }

    public function testShouldComputeTextWidthWithDefaultSize(): void
    {
        $width = $this->calculator->calculateWidth('MIT');
        $this->assertIsFloat($width);
        $this->assertGreaterThan(0, $width);
    }
}
