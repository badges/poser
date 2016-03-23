<?php

namespace spec\PUGX\Poser\Render;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use PUGX\Poser\Badge;

class SvgRenderSpec extends ObjectBehavior
{
    function let($calculator)
    {
        $calculator->beADoubleOf('\PUGX\Poser\Calculator\TextSizeCalculatorInterface');
        $calculator->calculateWidth(Argument::any())->willReturn(20);
        $this->beConstructedWith($calculator);
    }

    function it_should_render_a_svg()
    {
        $badge = Badge::fromURI('version-stable-97CA00.svg');
        $this->render($badge)->shouldBeAValidSVGImage();
    }

    function it_should_not_render_an_invalid_svg($calculator)
    {
        $templatesDir = __DIR__ . '/../../../Fixtures/invalid_template';
        $this->beConstructedWith($calculator, $templatesDir);
        $badge = Badge::fromURI('version-stable-97CA00.svg');
        $this->shouldThrow(new \RuntimeException('Generated string is not a valid XML'))->duringRender($badge);
    }

    function it_should_not_render_non_svg_xml($calculator)
    {
        $templatesDir = __DIR__ . '/../../../Fixtures/xml_template';
        $this->beConstructedWith($calculator, $templatesDir);
        $badge = Badge::fromURI('version-stable-97CA00.svg');
        $this->shouldThrow(new \RuntimeException('Generated xml is not a SVG'))->duringRender($badge);
    }

    public function getMatchers()
    {
        return array(
            'beAValidSVGImage' => function ($subject) {

                $regex = '/^<svg.*width="((.|\n)*)<\/svg>$/';
                $matches = array();

                return preg_match($regex, (string) $subject, $matches, PREG_OFFSET_CAPTURE, 0);
            }
        );
    }
} 
