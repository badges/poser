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

use EasySVG;
use PUGX\Poser\Badge;
use PUGX\Poser\Calculator\TextSizeCalculatorInterface;

class SvgForTheBadgeRenderer extends LocalSvgRenderer
{
    public const VENDOR_TEXT_FONT    = __DIR__ . '/../Calculator/Font/RobotoMedium.svg';
    public const VALUE_TEXT_FONT     = __DIR__ . '/../Calculator/Font/MontserratExtraBold.svg';
    public const TEXT_FONT_SIZE      = 11;
    public const TEXT_FONT_COLOR     = '#FFFFFF';
    public const TEXT_LETTER_SPACING = 0.1;
    public const PADDING_X           = 12;
    public const Y_OFFSET_ROBOTO     = 2;

    private EasySVG $easy;

    public function __construct(
        ?EasySVG $easySVG = null,
        ?TextSizeCalculatorInterface $textSizeCalculator = null,
        ?string $templatesDirectory = null
    ) {
        parent::__construct($textSizeCalculator, $templatesDirectory);

        if (null === $easySVG) {
            $easySVG = new EasySVG();
        }

        $this->easy = $easySVG;
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

        $this->easy->clearSVG();
        $this->easy->setLetterSpacing(self::TEXT_LETTER_SPACING);
        $this->easy->setFont(self::VENDOR_TEXT_FONT, self::TEXT_FONT_SIZE, self::TEXT_FONT_COLOR);
        $vendorDimensions                      = $this->easy->textDimensions($parameters['vendor']);
        $parameters['vendorWidth']             = $vendorDimensions[0] + 2 * self::PADDING_X;
        $parameters['vendorTextStartPosition'] = self::PADDING_X;
        $parameters['vendorTextPath']          = $this->easy->addText($parameters['vendor'], $parameters['vendorTextStartPosition'], self::TEXT_FONT_SIZE / 2 + self::Y_OFFSET_ROBOTO)->asXML();

        $this->easy->clearSVG();
        $this->easy->setLetterSpacing(self::TEXT_LETTER_SPACING);
        $this->easy->setFont(self::VALUE_TEXT_FONT, self::TEXT_FONT_SIZE, self::TEXT_FONT_COLOR);
        $valueDimensions                      = $this->easy->textDimensions($parameters['value']);
        $parameters['valueWidth']             = $valueDimensions[0] + 2 * self::PADDING_X;
        $parameters['valueTextStartPosition'] = $parameters['vendorWidth'] + self::PADDING_X;
        $parameters['valueTextPath']          = $this->easy->addText($parameters['value'], $parameters['valueTextStartPosition'], self::TEXT_FONT_SIZE / 2)->asXML();

        $parameters['totalWidth'] = $parameters['valueWidth'] + $parameters['vendorWidth'];

        return $parameters;
    }
}
