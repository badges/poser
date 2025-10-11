<?php

declare(strict_types=1);

namespace PUGX\Poser\Tests\Render;

use PHPUnit\Framework\TestCase;
use PUGX\Poser\Badge;
use PUGX\Poser\Calculator\TextSizeCalculatorInterface;
use PUGX\Poser\Render\SvgPlasticRender;

class SvgPlasticRenderTest extends TestCase
{
    private TextSizeCalculatorInterface $calculator;
    private SvgPlasticRender $render;

    protected function setUp(): void
    {
        $this->calculator = $this->createMock(TextSizeCalculatorInterface::class);
        $this->calculator->method('calculateWidth')->willReturn(20.0);
        $this->render = new SvgPlasticRender($this->calculator);
    }

    public function testShouldRenderASvg(): void
    {
        $badge = Badge::fromURI('version-stable-97CA00.svg?style=plastic');
        $image = $this->render->render($badge);

        $this->assertValidSVGImage((string) $image);
    }

    public function testGetBadgeStyle(): void
    {
        $this->assertEquals('plastic', $this->render->getBadgeStyle());
    }

    public function testShouldNotRenderAnInvalidSvg(): void
    {
        $templatesDir = __DIR__ . '/../Fixtures/invalid_template';
        $render       = new SvgPlasticRender($this->calculator, $templatesDir);
        $badge        = Badge::fromURI('version-stable-97CA00.svg?style=plastic');

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Generated string is not a valid XML');

        $render->render($badge);
    }

    public function testShouldNotRenderNonSvgXml(): void
    {
        $templatesDir = __DIR__ . '/../Fixtures/xml_template';
        $render       = new SvgPlasticRender($this->calculator, $templatesDir);
        $badge        = Badge::fromURI('version-stable-97CA00.svg?style=plastic');

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Generated xml is not a SVG');

        $render->render($badge);
    }

    private function assertValidSVGImage(string $svg): void
    {
        $regex = '/^<svg.*width="((.|\n)*)<\/svg>$/';
        $this->assertMatchesRegularExpression($regex, $svg);
    }
}
