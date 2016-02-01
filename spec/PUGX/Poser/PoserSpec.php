<?php

namespace spec\PUGX\Poser;

use PhpSpec\ObjectBehavior;
use PUGX\Poser\Render\SvgFlatRender;

class PoserSpec extends ObjectBehavior
{
    public function let()
    {
        $render = new SvgFlatRender();
        $this->beConstructedWith([$render]);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('PUGX\Poser\Poser');
    }

    public function it_should_be_able_to_generate_an_svg_image()
    {
        $subject = 'stable';
        $status = 'v2.0';
        $color = '97CA00';
        $format = 'svg';

        $this->generate($subject, $status, $color, $format)->shouldBeAValidSVGImageContaining($subject, $status);
    }

    public function it_should_be_able_to_generate_an_svg_image_from_URI()
    {
        $subject = 'stable-v2.0-97CA00.svg';

        $this->generateFromURI($subject)->shouldBeAValidSVGImageContaining('stable', 'v2.0');
    }

    public function getMatchers()
    {
        return [
            'beAValidSVGImageContaining' => function ($object, $subject, $status) {

                    $regex = '/^<svg.*width="((.|\n)*)'.$subject.'((.|\n)*)'.$status.'((.|\n)*)<\/svg>$/';
                    $matches = [];

                    return preg_match($regex, $object, $matches, PREG_OFFSET_CAPTURE, 0);
             },
        ];
    }
}
