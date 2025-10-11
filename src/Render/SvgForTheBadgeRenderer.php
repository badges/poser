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

class SvgForTheBadgeRenderer extends LocalSvgRenderer
{
    public const TEXT_FONT           = __DIR__ . '/../Calculator/Font/DejaVuSans.ttf';
    public const TEXT_FONT_SIZE      = 10;
    public const TEXT_FONT_COLOR     = '#FFFFFF';
    public const TEXT_LETTER_SPACING = 0.1;
    public const PADDING_X           = 10;

    private string $fontPath;

    public function __construct(
        ?TextSizeCalculatorInterface $textSizeCalculator = null,
        ?string $templatesDirectory = null
    ) {
        parent::__construct($textSizeCalculator, $templatesDirectory);
        $this->fontPath = self::TEXT_FONT;
    }

    public function getBadgeStyle(): string
    {
        return 'for-the-badge';
    }

    protected function getTemplateName(): string
    {
        return $this->getBadgeStyle();
    }

    protected function buildParameters(Badge $badge): array
    {
        $parameters = parent::buildParameters($badge);

        $parameters['vendor'] = \mb_strtoupper($parameters['vendor']);
        $parameters['value']  = \mb_strtoupper($parameters['value']);

        $vendorWidth                       = $this->calculateTextWidth($parameters['vendor']);
        $parameters['vendorWidth']         = $vendorWidth + 2 * self::PADDING_X;
        $parameters['vendorStartPosition'] = \round($parameters['vendorWidth'] / 2, 1) + 1;

        $valueWidth                       = $this->calculateTextWidth($parameters['value']);
        $parameters['valueWidth']         = $valueWidth + 2 * self::PADDING_X;
        $parameters['valueStartPosition'] = $parameters['vendorWidth'] + \round($parameters['valueWidth'] / 2, 1) - 1;

        $parameters['totalWidth'] = $parameters['valueWidth'] + $parameters['vendorWidth'];

        return $parameters;
    }

    /**
     * Calculate text width using GD with letter spacing.
     */
    private function calculateTextWidth(string $text): float
    {
        $box           = \imagettfbbox(self::TEXT_FONT_SIZE, 0, $this->fontPath, $text);
        $baseWidth     = \abs($box[2] - $box[0]);
        $letterSpacing = \mb_strlen($text) * self::TEXT_LETTER_SPACING;

        return \round($baseWidth + $letterSpacing, 1);
    }
}
