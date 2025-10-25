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

class SvgSocialRender extends LocalSvgRenderer
{
    public const VENDOR_TEXT_FONT    = __DIR__ . '/../Calculator/Font/HelveticaNeue.svg';
    public const VALUE_TEXT_FONT     = __DIR__ . '/../Calculator/Font/HelveticaNeueBold.svg';
    public const TEXT_FONT_SIZE      = 10;
    public const TEXT_FONT_COLOR     = '#FFFFFF';
    public const TEXT_LETTER_SPACING = 0.1;
    public const PADDING_X           = 8;
    public const ARROW_WIDTH         = 6.5;

    private \EasySVG $easy;

    public function __construct(
        ?\EasySVG $easySVG = null,
        ?TextSizeCalculatorInterface $textSizeCalculator = null,
        ?string $templatesDirectory = null
    ) {
        parent::__construct($textSizeCalculator, $templatesDirectory);

        if (null === $easySVG) {
            $easySVG = new \EasySVG();
        }

        $this->easy = $easySVG;
    }

    public function getBadgeStyle(): string
    {
        return 'social';
    }

    protected function getTemplateName(): string
    {
        return $this->getBadgeStyle();
    }

    protected function buildParameters(Badge $badge): array
    {
        $parameters = parent::buildParameters($badge);

        $this->easy->clearSVG();
        $this->easy->setLetterSpacing(self::TEXT_LETTER_SPACING);
        $this->easy->setFont(self::VENDOR_TEXT_FONT, self::TEXT_FONT_SIZE, self::TEXT_FONT_COLOR);
        $vendorDimensions                  = $this->easy->textDimensions($parameters['vendor']);
        $parameters['vendorWidth']         = $vendorDimensions[0] + 2 * self::PADDING_X;
        $parameters['vendorStartPosition'] = \round($parameters['vendorWidth'] / 2);

        $this->easy->clearSVG();
        $this->easy->setLetterSpacing(self::TEXT_LETTER_SPACING);
        $this->easy->setFont(self::VALUE_TEXT_FONT, self::TEXT_FONT_SIZE, self::TEXT_FONT_COLOR);
        $valueDimensions                  = $this->easy->textDimensions($parameters['value']);
        $parameters['valueWidth']         = $valueDimensions[0] + 2 * self::PADDING_X;
        $parameters['valueStartPosition'] = $parameters['vendorStartPosition'] + \round(self::ARROW_WIDTH) + \round($parameters['valueWidth'] / 2);

        $parameters['vendorAndArrowWidth'] = $parameters['vendorWidth'] + self::ARROW_WIDTH;
        $parameters['totalWidth']          = $parameters['vendorAndArrowWidth'] + $parameters['valueWidth'];

        return $parameters;
    }
}
