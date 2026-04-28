<?php

/*
 * This file is part of the badge-poser package.
 *
 * (c) PUGX <http://pugx.github.io/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PUGX\Poser\Calculator;

use Cog\SvgFont\FontList;
use Cog\Unicode\UnicodeString;

/**
 * SVG-based text size calculator using SVG fonts.
 *
 * This calculator provides more accurate text measurements using SVG fonts
 * but requires additional dependencies (cog/svg-font and cog/unicode).
 *
 * @author Anton Komarev <anton@komarev.com>
 */
class SvgTextSizeCalculator implements TextSizeCalculatorInterface
{
    /**
     * Calculate the width of the text box using SVG fonts.
     *
     * @throws \RuntimeException If SVG font dependencies are not available
     */
    public function calculateWidth(string $text, int $size = self::TEXT_SIZE): float
    {
        if (!\class_exists(FontList::class)) {
            throw new \RuntimeException('SVG font dependencies not available. Please install cog/svg-font and cog/unicode packages, or use GDTextSizeCalculator instead.');
        }

        try {
            $font          = FontList::ofFile(__DIR__ . '/Font/DejaVuSans.svg')->getById('DejaVuSansBook');
            $letterSpacing = 0.0;

            $width = $font->computeStringWidth(
                UnicodeString::of($text),
                $size,
                $letterSpacing,
            );

            $shieldPaddingX = self::SHIELD_PADDING_EXTERNAL + self::SHIELD_PADDING_INTERNAL;

            return \round($width + $shieldPaddingX, 1);
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to calculate text width with SVG fonts: ' . $e->getMessage(), 0, $e);
        }
    }
}
