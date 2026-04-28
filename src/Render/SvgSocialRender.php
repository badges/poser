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

/**
 * Renderer for the "social" style.
 *
 * The social badge has a 6 px gap between the left (vendor) pill and the
 * right (value) pill.  That gap widens totalWidth and shifts the value
 * text centre, but the individual pill widths are unchanged.
 *
 * Template variables specific to social.svg:
 *   • valueRectX        – x origin of the right pill rect  (vendorWidth + GAP + 0.5)
 *   • vendorWidthMinus1 – vendorWidth - 1  (used for the left pill stroke rect)
 *   • valueWidthMinus1  – valueWidth  - 1  (used for the right pill stroke rect)
 *   • separatorX        – x of the small chevron separator  (vendorWidth + 0.5)
 *   • valueStartX       – 10× text centre of the right pill (accounts for the gap)
 */
class SvgSocialRender extends SvgBaseRenderer
{
    private const PILL_GAP = 3;

    public function getBadgeStyle(): string
    {
        return 'social';
    }

    protected function buildParameters(Badge $badge): array
    {
        $parameters = parent::buildParameters($badge);

        $vendorWidth                     = $parameters['vendorWidth'];
        $valueWidth                      = $parameters['valueWidth'];
        $statusW                         = (int) \round((int) $parameters['valueTextLength'] / 10);
        $parameters['totalWidth']        = $vendorWidth + self::PILL_GAP + $valueWidth;
        $parameters['valueRectX']        = $vendorWidth + self::PILL_GAP + 0.5;
        $parameters['vendorWidthMinus1'] = $vendorWidth - 1;
        $parameters['valueWidthMinus1']  = $valueWidth - 1;
        $parameters['separatorX']        = $vendorWidth + 0.5;
        $valueCenter                     = $vendorWidth + self::PILL_GAP + self::PADDING_H + ($statusW / 2);
        $parameters['valueStartX']       = (int) \round($valueCenter * 10);

        return $parameters;
    }
}
