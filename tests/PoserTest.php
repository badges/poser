<?php

declare(strict_types=1);

namespace PUGX\Poser\Tests;

use PHPUnit\Framework\TestCase;
use PUGX\Poser\Poser;
use PUGX\Poser\Render\SvgFlatRender;
use PUGX\Poser\Render\SvgFlatSquareRender;

class PoserTest extends TestCase
{
    private Poser $poser;

    protected function setUp(): void
    {
        $this->poser = new Poser([
            new SvgFlatRender(),
            new SvgFlatSquareRender(),
        ]);
    }

    /**
     * @test
     */
    public function itIsInitializable(): void
    {
        $this->assertInstanceOf(Poser::class, $this->poser);
    }

    /**
     * @test
     */
    public function itShouldBeAbleToGenerateAnSvgImage(): void
    {
        $subject = 'stable';
        $status  = 'v2.0';
        $color   = '97CA00';
        $style   = 'flat';
        $format  = 'svg';

        $image = $this->poser->generate($subject, $status, $color, $style, $format);

        $this->assertValidSVGImageContaining((string) $image, $subject, $status);
    }

    /**
     * @test
     */
    public function itShouldBeAbleToGenerateAnSvgImageFromURI(): void
    {
        $subject = 'stable-v2.0-97CA00.svg';

        $image = $this->poser->generateFromURI($subject);

        $this->assertValidSVGImageContaining((string) $image, 'stable', 'v2.0');
    }

    /**
     * @test
     */
    public function itShouldBeAbleToGenerateAnSvgImageFromURIWithoutFileExtension(): void
    {
        $subject = 'stable-v2.0-97CA00';

        $image = $this->poser->generateFromURI($subject);

        $this->assertValidSVGImageContaining((string) $image, 'stable', 'v2.0');
    }

    /**
     * @test
     */
    public function itShouldBeAbleToGenerateAnSvgImageFromURIWithStyle(): void
    {
        $subject = 'stable-v2.0-97CA00.svg?style=flat-square';

        $image = $this->poser->generateFromURI($subject);

        $this->assertValidSVGImageContaining((string) $image, 'stable', 'v2.0');
    }

    /**
     * @test
     */
    public function itThrowsExceptionWhenGeneratingAnSvgImageWithBadURI(): void
    {
        $subject = 'stable-v2.0-';

        $this->expectException(\InvalidArgumentException::class);
        $this->poser->generateFromURI($subject);
    }

    private function assertValidSVGImageContaining(string $svg, string $subject, string $status): void
    {
        $regex = '/^<svg.*width="((.|\n)*)' . \preg_quote($subject, '/') . '((.|\n)*)' . \preg_quote($status, '/') . '((.|\n)*)<\/svg>$/';

        $this->assertMatchesRegularExpression($regex, $svg);
    }
}
