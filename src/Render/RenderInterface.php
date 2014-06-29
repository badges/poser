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

interface RenderInterface
{
    /**
     * Render a badge.
     *
     * @param Badge $badge
     *
     * @return \PUGX\Poser\Image
     */
    public function render(Badge $badge);

    /**
     * @return array the list of the supported format eg array('svg')
     */
    public function supportedFormats();
}
