<?php

namespace spec\PUGX\Poser;

use PhpSpec\ObjectBehavior;
use PUGX\Poser\Poser;
use PUGX\Poser\Render\SvgFlatRender;

class PoserSpec extends ObjectBehavior
{
    public function let(): void
    {
        $render = new SvgFlatRender();
        $this->beConstructedWith([$render]);
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(Poser::class);
    }

    public function it_should_be_able_to_generate_an_svg_image(): void
    {
        $subject = 'stable';
        $status  = 'v2.0';
        $color   = '97CA00';
        $format  = 'svg';

        $this->generate($subject, $status, $color, $format)->shouldBeAValidSVGImageContaining($subject, $status);
    }

    public function it_should_be_able_to_generate_an_svg_image_from_URI(): void
    {
        $subject = 'stable-v2.0-97CA00.svg';

        $this->generateFromURI($subject)->shouldBeAValidSVGImageContaining('stable', 'v2.0');
    }

    public function getMatchers(): array
    {
        return [
            'beAValidSVGImageContaining' => function ($object, $subject, $status) {
                $regex = '/^<svg.*width="((.|\n)*)' . $subject . '((.|\n)*)' . $status . '((.|\n)*)<\/svg>$/';
                $matches = [];

                return \preg_match($regex, $object, $matches, PREG_OFFSET_CAPTURE, 0);
            },
        ];
    }
}
