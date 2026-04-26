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
 *   • Badge height = 28px → logo sits at y=7 (vertically centred).
 *   • Both labels are uppercased.
 *   • Wider padding: 10px each side instead of 5px.
 *   • No EasySVG dependency – uses the same GDTextSizeCalculator as every
 *     other renderer.  The uppercased string is measured directly.
 */
class SvgForTheBadgeRenderer extends LocalSvgRenderer
{
    /**
     * Wider padding for this style.
     */
    private const FTB_PADDING = 0;

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

    /**
     * for-the-badge badges are 28px tall; logo must be at y=7 to be centred.
     */
    protected function logoY(): int
    {
        return 7;
    }

    protected function buildParameters(Badge $badge): array
    {
        $parameters = parent::buildParameters($badge);

        $hasLogo    = (bool) $badge->getLogo();
        $logoOffset = $hasLogo ? (self::LOGO_WIDTH + self::LOGO_TEXT_GAP) : 0;
        $vendorText = mb_strtoupper($badge->getSubject());
        $valueText  = mb_strtoupper($badge->getStatus());
        $subjectW = (int) round($this->stringWidth($vendorText));
        $statusW  = (int) round($this->stringWidth($valueText));
        $vendorWidth = self::FTB_PADDING + $logoOffset + $subjectW + self::FTB_PADDING;
        $valueWidth  = self::FTB_PADDING + $statusW + self::FTB_PADDING;
        $vendorCenter = self::FTB_PADDING + $logoOffset + ($subjectW / 2);
        $valueCenter  = $vendorWidth + self::FTB_PADDING + ($statusW / 2);
        $vendorStartX  = (int) round($vendorCenter * 10);
        $valueStartX   = (int) round($valueCenter  * 10);
        $vendorTextLen = $subjectW * 10;
        $valueTextLen  = $statusW  * 10;

        $parameters['vendor']           = $vendorText;
        $parameters['value']            = $valueText;
        $parameters['vendorUpper']      = $vendorText;
        $parameters['valueUpper']       = $valueText;
        $parameters['vendorWidth']      = $vendorWidth;
        $parameters['valueWidth']       = $valueWidth;
        $parameters['totalWidth']       = $vendorWidth + $valueWidth;
        $parameters['vendorStartX']     = $vendorStartX;
        $parameters['valueStartX']      = $valueStartX;
        $parameters['vendorTextLength'] = $vendorTextLen;
        $parameters['valueTextLength']  = $valueTextLen;

        return $parameters;
    }
}