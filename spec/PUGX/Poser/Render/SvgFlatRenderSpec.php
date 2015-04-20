<?php

namespace spec\PUGX\Poser\Render;

use PhpSpec\Exception\Exception;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use PUGX\Poser\Badge;
use PUGX\Poser\Calculator\GDTextSizeCalculator;
use PUGX\Poser\Calculator\TextSizeCalculatorInterface;

class SvgFlatRenderSpec extends ObjectBehavior
{
    function let(TextSizeCalculatorInterface $calculator)
    {
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
        $template  = <<<EOF
<svg xmlns="http://www.w3.org/2000/svg" width="40" height="20">
    <linearGradient id="b" x2="0" y2="100%">
        <stop offset="0" stop-color="#bbb" stop-opacity=".1"/>
        <stop offset="1" stop-opacity=".1"/>
    </linearGradient>
    <mask id="a">
        <rect width="40" height="20" rx="3" fill="#fff"/>
    </mask>
    <g mask="url(#a)">
        <path fill="#555" d="M0 0h20v20H0z"/>
        <path fill="#007ec6" d="M20 0h31v20H20z"/>
        <path fill="url(#b)" d="M0 0h40v20H0z"/>
    </g>
    <g fill="#fff" text-anchor="middle" font-family="DejaVu Sans,Verdana,Geneva,sans-serif" font-size="11">
        <text x="25.5" y="15" fill="#010101" fill-opacity=".3">license</text>
        <text x="25.5" y="14">license</text>
        <text x="63.5" y="15" fill="#010101" fill-opacity=".3">MIT</text>
        <text x="63.5" y="14">MIT</text>
    </g>
</svg>
EOF;

        $badge = Badge::fromURI('license-MIT-blue.svg');
        $this->render($badge)->__toString()->shouldBeLike($template);
    }


} 