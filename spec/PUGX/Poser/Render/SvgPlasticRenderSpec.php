<?php

namespace spec\PUGX\Poser\Render;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use PUGX\Poser\Badge;
use PUGX\Poser\Calculator\TextSizeCalculatorInterface;

class SvgPlasticRenderSpec extends ObjectBehavior
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

    public function it_should_not_render_an_invalid_svg($calculator): void
    {
        $templatesDir = __DIR__ . '/../../../Fixtures/invalid_template';
        $this->beConstructedWith($calculator, $templatesDir);
        $badge = Badge::fromURI('version-stable-97CA00.svg');
        $this->shouldThrow(new \RuntimeException('Generated string is not a valid XML'))->duringRender($badge);
    }

    public function it_should_not_render_non_svg_xml($calculator): void
    {
        $templatesDir = __DIR__ . '/../../../Fixtures/xml_template';
        $this->beConstructedWith($calculator, $templatesDir);
        $badge = Badge::fromURI('version-stable-97CA00.svg');
        $this->shouldThrow(new \RuntimeException('Generated xml is not a SVG'))->duringRender($badge);
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
}
