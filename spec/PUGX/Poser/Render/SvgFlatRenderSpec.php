<?php

namespace spec\PUGX\Poser\Render;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use PUGX\Poser\Badge;

class SvgFlatRenderSpec extends ObjectBehavior
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

    public function getMatchers()
    {
        return array(
            'beAValidSVGImage' => function($subject) {

                $regex = '/^<svg.*width="((.|\n)*)<\/svg>$/';
                $matches = array();

                return preg_match($regex, (string) $subject, $matches, PREG_OFFSET_CAPTURE, 0);
            }
        );
    }

    function it_should_render_a_license_mit_exactly_like_this_svg()
    {
        $fixture = __DIR__ . '/../../../Fixtures/flat.svg';
        $template = file_get_contents($fixture);
        $badge = Badge::fromURI('license-MIT-blue.svg');
        $this->render($badge)->__toString()->shouldBeLike($template);
    }
}
