<?php

declare(strict_types=1);

namespace PUGX\Poser\Tests\Render;

use PHPUnit\Framework\TestCase;
use PUGX\Poser\Badge;
use PUGX\Poser\Calculator\TextSizeCalculatorInterface;
use PUGX\Poser\Render\SvgFlatRender;

class SvgFlatRenderTest extends TestCase
{
    private TextSizeCalculatorInterface $calculator;
    private SvgFlatRender $render;

    protected function setUp(): void
    {
        $this->calculator = $this->createMock(TextSizeCalculatorInterface::class);
        $this->calculator->method('calculateWidth')->willReturn(20.0);
        $this->render = new SvgFlatRender($this->calculator);
    }

    public function testShouldRenderASvg(): void
    {
        $badge = Badge::fromURI('version-stable-97CA00.svg');
        $image = $this->render->render($badge);

        $this->assertValidSVGImage((string) $image);
    }

    public function testShouldRenderALicenseMitExactlyLikeThisSvg(): void
    {
        $fixture = __DIR__ . '/../Fixtures/flat.svg';
        $template = \file_get_contents($fixture);
        $badge = Badge::fromURI('license-MIT-blue.svg');
        $image = $this->render->render($badge);

        $this->assertEquals($template, (string) $image);
    }

    public function testGetBadgeStyle(): void
    {
        $this->assertEquals('flat', $this->render->getBadgeStyle());
    }

    private function assertValidSVGImage(string $svg): void
    {
        $regex = '/^<svg.*width="((.|\n)*)<\/svg>$/';
        $this->assertMatchesRegularExpression($regex, $svg);
    }
}
