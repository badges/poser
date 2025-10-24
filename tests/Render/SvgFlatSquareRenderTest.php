<?php

declare(strict_types=1);

namespace PUGX\Poser\Tests\Render;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use PUGX\Poser\Badge;
use PUGX\Poser\Calculator\TextSizeCalculatorInterface;
use PUGX\Poser\Render\SvgFlatSquareRender;

class SvgFlatSquareRenderTest extends TestCase
{
    private TextSizeCalculatorInterface $calculator;
    private SvgFlatSquareRender $render;

    protected function setUp(): void
    {
        $this->calculator = $this->createMock(TextSizeCalculatorInterface::class);
        $this->calculator->method('calculateWidth')->willReturn(20.0);
        $this->render = new SvgFlatSquareRender($this->calculator);
    }

    #[Test]
    public function itShouldRenderASvg(): void
    {
        $badge = Badge::fromURI('version-stable-97CA00.svg?style=flat-square');
        $image = $this->render->render($badge);

        $this->assertValidSVGImage((string) $image);
    }

    #[Test]
    public function itShouldRenderALicenseMitExactlyLikeThisSvg(): void
    {
        $fixture  = __DIR__ . '/../Fixtures/flat-square.svg';
        $template = \file_get_contents($fixture);
        $badge    = Badge::fromURI('license-MIT-blue.svg?style=flat-square');
        $image    = $this->render->render($badge);

        $this->assertEquals($template, (string) $image);
    }

    #[Test]
    public function itShouldReturnCorrectBadgeStyle(): void
    {
        $this->assertEquals('flat-square', $this->render->getBadgeStyle());
    }

    private function assertValidSVGImage(string $svg): void
    {
        $regex = '/^<svg.*width="((.|\n)*)<\/svg>$/';
        $this->assertMatchesRegularExpression($regex, $svg);
    }
}
