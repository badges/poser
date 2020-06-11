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
    public const TEXT_SIZE               = 11;
    public const SHIELD_PADDING_EXTERNAL = 6;
    public const SHIELD_PADDING_INTERNAL = 4;

    /**
     * Calculate the width of the text box.
     */
    public function calculateWidth(string $text, int $size = self::TEXT_SIZE): float;
}
