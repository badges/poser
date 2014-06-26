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

interface TextSizeCalculatorInterface
{
    const TEXT_SIZE               = 11;
    const SHIELD_PADDING_EXTERNAL = 6;
    const SHIELD_PADDING_INTERNAL = 4;

    /**
     * Calculate the width of the text box.
     *
     * @param string $text
     * @param int    $size
     *
     * @return float
     */
    public function calculateWidth($text, $size = self::TEXT_SIZE);
}
