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
    const TEXT_FONT             = '/Font/DejaVuSans.ttf';

    /** @var string */
    protected $fontPath;

    public function __construct()
    {
        $this->fontPath = __DIR__ . self::TEXT_FONT;
    }

    /**
     * Calculate the width of the text box.
     *
     * @param string $text
     * @param int    $size
     *
     * @return float
     */
    public function calculateWidth($text, $size = self::TEXT_SIZE)
    {
        $size = $this->convertToPt($size);
        $box  = imagettfbbox($size, 0, $this->fontPath, $text);

        return round(abs($box[2] - $box[0]) + self::SHIELD_PADDING_EXTERNAL + self::SHIELD_PADDING_INTERNAL,  1);
    }

    private function convertToPt($pixels)
    {
        return round($pixels * 0.75, 1);
    }
}
