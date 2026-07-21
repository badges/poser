<?php

declare(strict_types=1);

namespace PUGX\Poser\Tests\Render;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use PUGX\Poser\Badge;
use PUGX\Poser\Calculator\TextSizeCalculatorInterface;
use PUGX\Poser\Render\SvgForTheBadgeRenderer;

class SvgForTheBadgeRendererTest extends TestCase
{
    private TextSizeCalculatorInterface $calculator;
    private SvgForTheBadgeRenderer $render;

    protected function setUp(): void
    {
        $this->calculator = $this->createStub(TextSizeCalculatorInterface::class);
        $this->calculator->method('calculateWidth')->willReturnCallback(static function (string $text): float {
            $widths = [
                'VERSION'   => 53.0,
                'STABLE'    => 43.0,
                'LICENSE'   => 55.0,
                'MIT'       => 28.0,
                'BUILD'     => 42.0,
                'PASSING'   => 57.0,
                'TEST'      => 30.0,
                'LOWERCASE' => 67.0,
            ];

            return $widths[$text] ?? (float) (\strlen($text) * 8);
        });
        $this->render     = new SvgForTheBadgeRenderer($this->calculator);
    }

    #[Test]
    public function itShouldRenderASvg(): void
    {
        $badge = Badge::fromURI('version-stable-97CA00.svg?style=for-the-badge');
        $image = $this->render->render($badge);

        $this->assertValidSVGImage((string) $image);
    }

    #[Test]
    public function itShouldRenderALicenseMitExactlyLikeThisSvg(): void
    {
        $fixture  = __DIR__ . '/../Fixtures/for-the-badge.svg';
        $template = \file_get_contents($fixture);
        $badge    = Badge::fromURI('license-MIT-blue.svg?style=for-the-badge');
        $image    = $this->render->render($badge);

        $this->assertEquals($template, (string) $image);
    }

    #[Test]
    public function itShouldRenderWithLogoExactlyLikeThisSvg(): void
    {
        $fixture  = __DIR__ . '/../Fixtures/for-the-badge-with-logo.svg';
        $template = \file_get_contents($fixture);
        $badge    = Badge::fromURI('build-passing-brightgreen.svg?logo=data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNCAyNCI+PHBhdGggZmlsbD0iI2ZmZiIgZD0iTTEyIDJDNi40OCAyIDIgNi40OCAyIDEyczQuNDggMTAgMTAgMTAgMTAtNC40OCAxMC0xMFMxNy41MiAyIDEyIDJ6bS0xIDE3SDl2LTJoMnYyeiIvPjwvc3ZnPg==&style=for-the-badge');
        $image    = $this->render->render($badge);

        $this->assertEquals($template, (string) $image);
    }

    #[Test]
    public function itShouldReturnCorrectBadgeStyle(): void
    {
        $this->assertEquals('for-the-badge', $this->render->getBadgeStyle());
    }

    #[Test]
    public function itShouldUseCorrectLogoYPosition(): void
    {
        $badge = Badge::fromURI('test-test-000000.svg?logo=data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNCAyNCI+PHBhdGggZmlsbD0iI2ZmZiIgZD0iTTEyIDJDNi40OCAyIDIgNi40OCAyIDEyczQuNDggMTAgMTAgMTAgMTAtNC40OCAxMC0xMFMxNy41MiAyIDEyIDJ6bS0xIDE3SDl2LTJoMnYyeiIvPjwvc3ZnPg==&style=for-the-badge');
        $image = (string) $this->render->render($badge);

        $this->assertStringContainsString('y="7"', $image);
    }

    #[Test]
    public function itShouldUppercaseText(): void
    {
        $badge = Badge::fromURI('test-lowercase-000000.svg?style=for-the-badge');
        $image = (string) $this->render->render($badge);

        $this->assertStringContainsString('TEST', $image);
        $this->assertStringContainsString('LOWERCASE', $image);
    }

    #[Test]
    public function itShouldUseCorrectPadding(): void
    {
        $badge = Badge::fromURI('test-test-000000.svg?style=for-the-badge');
        $image = (string) $this->render->render($badge);

        $this->assertValidSVGImage($image);
        $this->assertStringContainsString('<rect', $image);
        $this->assertStringContainsString('<text', $image);
    }

    private function assertValidSVGImage(string $svg): void
    {
        $regex = '/^<svg.*width="((.|\n)*)<\/svg>$/';
        $this->assertMatchesRegularExpression($regex, $svg);
    }
}
