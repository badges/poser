<?php

declare(strict_types=1);

namespace PUGX\Poser\Tests\Render;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use PUGX\Poser\Badge;
use PUGX\Poser\Calculator\TextSizeCalculatorInterface;
use PUGX\Poser\Render\SvgSocialRender;

class SvgSocialRenderTest extends TestCase
{
    private TextSizeCalculatorInterface $calculator;
    private SvgSocialRender $render;

    protected function setUp(): void
    {
        $this->calculator = $this->createStub(TextSizeCalculatorInterface::class);
        $this->calculator->method('calculateWidth')->willReturnCallback(static function (string $text): float {
            $widths = [
                'twitter' => 46.0,
                'follow'  => 43.0,
                'github'  => 45.0,
                'stars'   => 38.0,
                'test'    => 24.0,
            ];

            return $widths[$text] ?? (float) (\strlen($text) * 7);
        });
        $this->render     = new SvgSocialRender($this->calculator);
    }

    #[Test]
    public function itShouldRenderASvg(): void
    {
        $badge = Badge::fromURI('twitter-follow-1da1f2.svg?style=social&logo=twitter');
        $image = $this->render->render($badge);

        $this->assertValidSVGImage((string) $image);
    }

    #[Test]
    public function itShouldRenderTwitterSocialExactlyLikeThisSvg(): void
    {
        $fixture  = __DIR__ . '/../Fixtures/social.svg';
        $template = \file_get_contents($fixture);
        $badge    = Badge::fromURI('twitter-follow-1da1f2.svg?style=social&logo=twitter');
        $image    = $this->render->render($badge);

        $this->assertEquals($template, (string) $image);
    }

    #[Test]
    public function itShouldRenderGithubSocialExactlyLikeThisSvg(): void
    {
        $fixture  = __DIR__ . '/../Fixtures/social-with-logo.svg';
        $template = \file_get_contents($fixture);
        $badge    = Badge::fromURI('github-stars-333.svg?style=social&logo=github');
        $image    = $this->render->render($badge);

        $this->assertEquals($template, (string) $image);
    }

    #[Test]
    public function itShouldReturnCorrectBadgeStyle(): void
    {
        $this->assertEquals('social', $this->render->getBadgeStyle());
    }

    #[Test]
    public function itShouldApplyPillGapToTotalWidth(): void
    {
        $badge = Badge::fromURI('test-test-000000.svg?style=social');
        $image = (string) $this->render->render($badge);

        $this->assertValidSVGImage($image);
        $this->assertStringContainsString('<rect', $image);
        $this->assertStringContainsString('<text', $image);
    }

    #[Test]
    public function itShouldUseCorrectValueRectX(): void
    {
        $badge = Badge::fromURI('test-test-000000.svg?style=social');
        $image = (string) $this->render->render($badge);

        $this->assertValidSVGImage($image);
    }

    #[Test]
    public function itShouldShiftValueStartXForGap(): void
    {
        $badge = Badge::fromURI('test-test-000000.svg?style=social');
        $image = (string) $this->render->render($badge);

        $this->assertValidSVGImage($image);
        $this->assertStringContainsString('transform="scale(.1)"', $image);
    }

    #[Test]
    public function itShouldRenderWithLogo(): void
    {
        $badge = Badge::fromURI('test-test-000000.svg?style=social&logo=data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNCAyNCI+PHBhdGggZmlsbD0iI2ZmZiIgZD0iTTEyIDJDNi40OCAyIDIgNi40OCAyIDEyczQuNDggMTAgMTAgMTAgMTAtNC40OCAxMC0xMFMxNy41MiAyIDEyIDJ6bS0xIDE3SDl2LTJoMnYyeiIvPjwvc3ZnPg==');
        $image = (string) $this->render->render($badge);

        $this->assertValidSVGImage($image);
        $this->assertStringContainsString('<image', $image);
    }

    #[Test]
    public function itShouldHaveCorrectSeparatorPosition(): void
    {
        $badge = Badge::fromURI('test-test-000000.svg?style=social');
        $image = (string) $this->render->render($badge);

        $this->assertValidSVGImage($image);
    }

    private function assertValidSVGImage(string $svg): void
    {
        $regex = '/^<svg.*width="((.|\n)*)<\/svg>$/';
        $this->assertMatchesRegularExpression($regex, $svg);
    }
}
