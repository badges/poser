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

class GDTextSizeCalculator implements TextSizeCalculatorInterface
{
    public const TEXT_FONT = '/Font/DejaVuSans.ttf';

    protected string $fontPath;

    public function __construct()
    {
        if (0 === \strpos(__DIR__, 'phar://')) {
            //Hack to work with phar virtual environment
            $prefixFontPath = './src/Calculator';
        } else {
            $prefixFontPath = __DIR__;
        }

        $this->fontPath = $prefixFontPath . self::TEXT_FONT;
    }

    /**
     * Calculate the width of the text box.
     */
    public function calculateWidth(string $text, int $size = self::TEXT_SIZE): float
    {
        $size = $this->convertToPt($size);

        $box  = \imagettfbbox($size, 0, $this->fontPath, $text);

        return \round(\abs($box[2] - $box[0]) + self::SHIELD_PADDING_EXTERNAL + self::SHIELD_PADDING_INTERNAL, 1);
    }

    private function convertToPt(int $pixels): float
    {
        return \round($pixels * 0.75, 1);
    }
}
