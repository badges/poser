<?php

namespace spec\PUGX\Poser;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use PUGX\Poser\Render\SvgFlatRender;

class PoserSpec extends ObjectBehavior
{
    function let()
    {
        $render = new SvgFlatRender();
        $this->beConstructedWith(array($render));
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('PUGX\Poser\Poser');
    }

    function it_should_be_able_to_generate_an_svg_image()
    {
        $subject = 'stable';
        $status = 'v2.0';
        $color = '97CA00';
        $format = 'svg';

        $this->generate($subject, $status, $color, $format)->shouldBeAValidSVGImageContaining($subject, $status);
    }

    function it_should_be_able_to_generate_an_svg_image_from_URI()
    {
        $subject = 'stable-v2.0-97CA00.svg';

        $this->generateFromURI($subject)->shouldBeAValidSVGImageContaining('stable', 'v2.0');
    }


    public function getMatchers()
    {
        return array(
            'beAValidSVGImageContaining' => function($object, $subject, $status) {
                    $regex = '/^<svg.*width="((.|\n)*)'.$subject.'((.|\n)*)'.$status.'((.|\n)*)<\/svg>$/';
                    $matches = array();

                    return preg_match($regex, $object, $matches, PREG_OFFSET_CAPTURE, 0);
             },
        );
    }
}
