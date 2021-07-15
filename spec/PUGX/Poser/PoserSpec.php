<?php

namespace spec\PUGX\Poser;

use PhpSpec\ObjectBehavior;
use PUGX\Poser\Poser;
use PUGX\Poser\Render\SvgFlatRender;
use PUGX\Poser\Render\SvgFlatSquareRender;

class PoserSpec extends ObjectBehavior
{
    public function let(): void
    {
        $this->beConstructedWith([
            new SvgFlatRender(),
            new SvgFlatSquareRender(),
        ]);
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
        $style   = 'flat';
        $format  = 'svg';

        $this->generate($subject, $status, $color, $style, $format)->shouldBeAValidSVGImageContaining($subject, $status);
    }

    public function it_should_be_able_to_generate_an_svg_image_from_URI(): void
    {
        $subject = 'stable-v2.0-97CA00.svg';

        $this->generateFromURI($subject)->shouldBeAValidSVGImageContaining('stable', 'v2.0');
    }

    public function it_should_be_able_to_generate_an_svg_image_from_URI_without_file_extension(): void
    {
        $subject = 'stable-v2.0-97CA00';

        $this->generateFromURI($subject)->shouldBeAValidSVGImageContaining('stable', 'v2.0');
    }

    public function it_should_be_able_to_generate_an_svg_image_from_URI_with_style(): void
    {
        $subject = 'stable-v2.0-97CA00.svg?style=flat-square';

        $this->generateFromURI($subject)->shouldBeAValidSVGImageContaining('stable', 'v2.0');
    }

    public function it_should_be_able_to_generate_an_svg_image_from_URI_with_empty_style(): void
    {
        $subject = 'stable-v2.0-97CA00.svg?style=';

        $this->generateFromURI($subject)->shouldBeAValidSVGImageContaining('stable', 'v2.0');
    }

    public function it_should_be_able_to_generate_an_svg_image_from_URI_with_empty_query(): void
    {
        $subject = 'stable-v2.0-97CA00.svg?';

        $this->generateFromURI($subject)->shouldBeAValidSVGImageContaining('stable', 'v2.0');
    }

    public function it_should_be_able_to_generate_an_svg_image_from_URI_without_file_extension_with_style(): void
    {
        $subject = 'stable-v2.0-97CA00?style=flat-square';

        $this->generateFromURI($subject)->shouldBeAValidSVGImageContaining('stable', 'v2.0');
    }

    public function it_should_throw_exception_on_generate_an_svg_image_with_bad_uri(): void
    {
        $subject = 'stable-v2.0-';

        $this->shouldThrow(\InvalidArgumentException::class)->during('generateFromURI', [$subject]);
    }

    public function getMatchers(): array
    {
        return [
            'beAValidSVGImageContaining' => function ($object, $subject, $status) {
                $regex = '/^<svg.*width="((.|\n)*)' . $subject . '((.|\n)*)' . $status . '((.|\n)*)<\/svg>$/';
                $matches = [];

                return \preg_match($regex, $object, $matches, \PREG_OFFSET_CAPTURE, 0);
            },
        ];
    }
}
