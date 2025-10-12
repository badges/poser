<?php

declare(strict_types=1);

namespace PUGX\Poser\Tests;

use PHPUnit\Framework\TestCase;
use PUGX\Poser\Badge;

class BadgeTest extends TestCase
{
    /**
     * @test
     */
    public function itIsInitializable(): void
    {
        $badge = new Badge('a', 'b', '97CA00', 'flat');
        $this->assertInstanceOf(Badge::class, $badge);
    }

    /**
     * @test
     */
    public function itShouldBeConstructedByFromURIFactoryMethod(): void
    {
        $assert = 'version-stable-97CA00.svg';
        $badge  = Badge::fromURI($assert);

        $this->assertEquals($assert, (string) $badge);
    }

    /**
     * @test
     */
    public function itShouldEscapeUnderscoresCorrectlyWhenConstructedFromURI(): void
    {
        $input       = 'I__m__liugg__io-b-97CA00.svg';
        $assertInput = 'I_m_liugg_io-b-97CA00.svg';
        $badge       = Badge::fromURI($input);

        $this->assertEquals($assertInput, (string) $badge);
    }

    /**
     * @test
     */
    public function itShouldConvertSingleUnderscoresToSpacesWhenConstructedFromURI(): void
    {
        $input       = 'I_m_liuggio-b-97CA00.svg';
        $assertInput = 'I m liuggio-b-97CA00.svg';
        $badge       = Badge::fromURI($input);

        $this->assertEquals($assertInput, (string) $badge);
    }

    /**
     * @test
     */
    public function itShouldEscapeDashesCorrectlyWhenConstructedFromURI(): void
    {
        $input       = 'I--m--liuggio-b-97CA00.svg';
        $assertInput = 'I-m-liuggio-b-97CA00.svg';
        $badge       = Badge::fromURI($input);

        $this->assertEquals($assertInput, (string) $badge);
    }

    /**
     * @test
     *
     * @dataProvider positiveConversionExamples
     */
    public function itShouldValidateAvailableColorSchemes(string $colorName): void
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
