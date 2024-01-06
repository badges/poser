<?php

namespace spec\PUGX\Poser\Calculator;

use PhpSpec\ObjectBehavior;

class SvgTextSizeCalculatorSpec extends ObjectBehavior
{
    public function it_should_compute_text_width(): void
    {
        $this->calculateWidth('MIT', 8)->shouldBeLike(24.1);
        $this->calculateWidth('MIT', 10)->shouldBeLike(27.7);
        $this->calculateWidth('MIT', 14)->shouldBeLike(34.8);
    }
}
