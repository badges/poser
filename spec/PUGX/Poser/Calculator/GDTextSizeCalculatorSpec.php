<?php

namespace spec\PUGX\Poser\Calculator;

use PhpSpec\ObjectBehavior;

class GDTextSizeCalculatorSpec extends ObjectBehavior
{
    public function it_should_compute_text_width(): void
    {
        $this->calculateWidth('MIT', 8)->shouldBeLike(24.0);
        $this->calculateWidth('MIT', 10)->shouldBeLike(29.0);
        $this->calculateWidth('MIT', 14)->shouldBeLike(34.0);
    }
}
