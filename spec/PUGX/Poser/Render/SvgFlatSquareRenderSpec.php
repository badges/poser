<?php

namespace spec\PUGX\Poser\Render;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use PUGX\Poser\Badge;
use PUGX\Poser\Calculator\TextSizeCalculatorInterface;

class SvgFlatSquareRenderSpec extends ObjectBehavior
{
    public function let($calculator): void
    {
        $calculator->beADoubleOf(TextSizeCalculatorInterface::class);
        $calculator->calculateWidth(Argument::any())->willReturn(20);
        $this->beConstructedWith($calculator);
    }

    public function it_should_render_a_svg(): void
    {
        $badge = Badge::fromURI('version-stable-97CA00.svg');
        $this->render($badge)->shouldBeAValidSVGImage();
    }

    public function getMatchers(): array
    {
        return [
            'beAValidSVGImage' => function ($subject) {
                $regex = '/^<svg.*width="((.|\n)*)<\/svg>$/';
                $matches = [];

                return \preg_match($regex, (string) $subject, $matches, \PREG_OFFSET_CAPTURE, 0);
            },
        ];
    }

    public function it_should_render_a_license_mit_exactly_like_this_svg(): void
    {
        $fixture  = __DIR__ . '/../../../Fixtures/flat-square.svg';
        $template = \file_get_contents($fixture);
        $badge    = Badge::fromURI('license-MIT-blue.svg');
        $this->render($badge)->__toString()->shouldBeLike($template);
    }
}
