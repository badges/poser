<?php

/*
 * This file is part of the badge-poser package.
 *
 * (c) PUGX <http://pugx.github.io/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PUGX\Poser\Render;

use PUGX\Poser\Badge;
use PUGX\Poser\Image;

interface RenderInterface
{
    /**
     * Render a badge
     */
    public function render(Badge $badge): Image;

    /**
     * @return string the style of the badge image eg. `flat`.
     */
    public function getBadgeStyle(): string;
}
