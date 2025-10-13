<?php

declare(strict_types=1);

namespace PUGX\Poser\Tests;

use PHPUnit\Framework\TestCase;
use PUGX\Poser\Badge;

class BadgeTest extends TestCase
{
    public function testIsInitializable(): void
    {
        $badge = new Badge('a', 'b', '97CA00', 'flat');
        $this->assertInstanceOf(Badge::class, $badge);
    }

    public function testShouldBeConstructedByFromURIFactoryMethod(): void
    {
        $assert = 'version-stable-97CA00.svg';
        $badge  = Badge::fromURI($assert);

        $this->assertEquals($assert, (string) $badge);
    }

    public function testShouldBeConstructedByFromURIFactoryMethodEscapingCorrectlyUnderscores(): void
    {
        $input       = 'I__m__liugg__io-b-97CA00.svg';
        $assertInput = 'I_m_liugg_io-b-97CA00.svg';
        $badge       = Badge::fromURI($input);

        $this->assertEquals($assertInput, (string) $badge);
    }

    public function testShouldBeConstructedByFromURIFactoryMethodEscapingCorrectlyWithSingleUnderscore(): void
    {
        $input       = 'I_m_liuggio-b-97CA00.svg';
        $assertInput = 'I m liuggio-b-97CA00.svg';
        $badge       = Badge::fromURI($input);

        $this->assertEquals($assertInput, (string) $badge);
    }

    public function testShouldBeConstructedByFromURIFactoryMethodEscapingCorrectlyWithDashes(): void
    {
        $input       = 'I--m--liuggio-b-97CA00.svg';
        $assertInput = 'I-m-liuggio-b-97CA00.svg';
        $badge       = Badge::fromURI($input);

        $this->assertEquals($assertInput, (string) $badge);
    }

    /**
     * @dataProvider positiveConversionExamples
     */
    public function testShouldValidateAvailableColorSchemes(string $colorName): void
    {
        $badge = new Badge('a', 'b', $colorName, 'flat');
        $this->assertIsString($badge->getHexColor());
    }

    public static function positiveConversionExamples(): array
    {
        $colorNames = Badge::getColorNamesAvailable();

        $data = [];
        foreach ($colorNames as $colorName) {
            $data[$colorName] = [$colorName];
        }

        return $data;
    }
}
