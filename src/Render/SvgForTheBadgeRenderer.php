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
use PUGX\Poser\Calculator\TextSizeCalculatorInterface;

/**
 * Renderer for the "for-the-badge" style.
 *
 * Differences vs. base:
 *   • Badge height = 28 px  →  logo sits at y = 7 (vertically centred).
 *   • Both labels are upper-cased before measuring and rendering.
 *   • Uses wider per-section padding (10 px each side) to match the
 *     reference Shields.io implementation for this style.
 */
class SvgForTheBadgeRenderer extends LocalSvgRenderer
{
    /**
     * Padding per side for the for-the-badge style.
     * Shields.io uses 10 px to give the uppercased text more breathing room.
     */
    private const FTB_PADDING = 3;

    public function __construct(
        ?TextSizeCalculatorInterface $textSizeCalculator = null,
        ?string $templatesDirectory = null
    ) {
        parent::__construct($textSizeCalculator, $templatesDirectory);
    }

    public function getBadgeStyle(): string
    {
        return 'for-the-badge';
    }

    protected function getTemplateName(): string
    {
        return $this->getBadgeStyle();
    }

    protected function logoY(): int
    {
        return 7;
    }

    protected function buildParameters(Badge $badge): array
    {
        $parameters    = parent::buildParameters($badge);
        $hasLogo       = (bool) $badge->getLogo();
        $logoOffset    = $hasLogo ? (self::LOGO_WIDTH + self::LOGO_TEXT_GAP) : 0;
        $vendorText    = \mb_strtoupper($badge->getSubject());
        $valueText     = \mb_strtoupper($badge->getStatus());
        $subjectW      = (int) \round($this->stringWidth($vendorText));
        $statusW       = (int) \round($this->stringWidth($valueText));
        $vendorWidth   = self::FTB_PADDING + $logoOffset + $subjectW + self::FTB_PADDING;
        $valueWidth    = self::FTB_PADDING + $statusW + self::FTB_PADDING;
        $totalWidth    = $vendorWidth + $valueWidth;
        $vendorCenter  = self::FTB_PADDING + $logoOffset + ($subjectW / 2);
        $valueCenter   = self::FTB_PADDING + ($statusW / 2);
        $vendorStartX  = (int) \round($vendorCenter * 10);
        $valueStartX   = (int) \round(($vendorWidth + $valueCenter) * 10);
        $vendorTextLen = $subjectW * 10;
        $valueTextLen  = $statusW * 10;

        $parameters['vendor']            = $vendorText;
        $parameters['value']             = $valueText;
        $parameters['vendorUpper']       = $vendorText;
        $parameters['valueUpper']        = $valueText;
        $parameters['vendorWidth']       = $vendorWidth;
        $parameters['valueWidth']        = $valueWidth;
        $parameters['totalWidth']        = $totalWidth;
        $parameters['vendorStartX']      = $vendorStartX;
        $parameters['valueStartX']       = $valueStartX;
        $parameters['vendorTextLength']  = $vendorTextLen;
        $parameters['valueTextLength']   = $valueTextLen;
        $parameters['vendorWidthMinus1'] = $vendorWidth - 1;
        $parameters['valueWidthMinus1']  = $valueWidth - 1;
        $parameters['valueRectX']        = $vendorWidth + 0.5;
        $parameters['separatorX']        = $vendorWidth + 0.5;

        return $parameters;
    }
}
