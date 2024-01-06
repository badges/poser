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
 * @author Anton Komarev <anton@komarev.com>
 */
class SvgTextSizeCalculator implements TextSizeCalculatorInterface
{
    /**
     * Calculate the width of the text box.
     */
    public function calculateWidth(string $text, int $size = self::TEXT_SIZE): float
    {
        $font = FontList::ofFile(__DIR__ . '/Font/DejaVuSans.svg')->getById('DejaVuSansBook');

        $letterSpacing = 0.0;

        $width = $font->computeStringWidth(
            UnicodeString::of($text),
            $size,
            $letterSpacing,
        );

        $shieldPaddingX = self::SHIELD_PADDING_EXTERNAL + self::SHIELD_PADDING_INTERNAL;

        return \round($width + $shieldPaddingX, 1);
    }
}
