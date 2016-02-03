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
use PUGX\Poser\Calculator\GDTextSizeCalculator;
use PUGX\Poser\Image;
/**
 * Class SvgFlatGenerator
 *
 * @author Giulio De Donato <liuggio@gmail.com>
 */
class SvgFlatSquareRender implements RenderInterface
{
    const VENDOR_COLOR            = '#555';
    private $textSizeCalculator;
/*
 private static $template  = <<<EOF
<svg xmlns="http://www.w3.org/2000/svg" width="{{ totalWidth }}" height="20">
    <linearGradient id="b" x2="0" y2="100%">
        <stop offset="0" stop-color="#bbb" stop-opacity=".1"/>
        <stop offset="1" stop-opacity=".1"/>
    </linearGradient>
    <mask id="a">
        <rect width="{{ totalWidth }}" height="20" rx="3" fill="#fff"/>
    </mask>
    <g mask="url(#a)">
        <path fill="#555" d="M0 0h{{ vendorWidth }}v20H0z"/>
        <path fill="#007ec6" d="M{{ vendorWidth }} 0h31v20H{{ vendorWidth }}z"/>
        <path fill="url(#b)" d="M0 0h{{ totalWidth }}v20H0z"/>
    </g>
    <g fill="#fff" text-anchor="middle" font-family="DejaVu Sans,Verdana,Geneva,sans-serif" font-size="11">
        <text x="25.5" y="15" fill="#010101" fill-opacity=".3">{{ vendor }}</text>
        <text x="25.5" y="14">{{ vendor }}</text>
        <text x="63.5" y="15" fill="#010101" fill-opacity=".3">{{ value }}</text>
        <text x="63.5" y="14">{{ value }}</text>
    </g>
</svg>
EOF;


quasi ok
ù    private static $template  = <<<EOF
<svg xmlns="http://www.w3.org/2000/svg" width="{{ totalWidth }}" height="20">
    <linearGradient id="smooth" x2="0" y2="100%">
    <stop offset="0" stop-color="#bbb" stop-opacity=".1"/>
    <stop offset="1" stop-opacity=".1"/>
    </linearGradient>

    <mask id="round">
    <rect width="{{ totalWidth }}" height="20" rx="3" fill="{{ vendorColor }}"/>
    </mask>

    <g mask="url(#round)">
    <rect width="{{ vendorWidth }}" height="20" fill="#fff"/>
    <rect x="{{ vendorWidth }}" width="{{ valueWidth }}" height="20" fill="{{ valueColor }}"/>
    <rect width="{{ totalWidth }}" height="20" fill="url(#smooth)"/>
    </g>

    <g fill="#fff" text-anchor="middle" font-family="DejaVu Sans,Verdana,Geneva,sans-serif" font-size="11">
    <text x="{{ vendorStartPosition }}" y="15" fill="#010101" fill-opacity=".3">{{ vendor }}</text>
        <text x="{{ vendorStartPosition }}" y="14">{{ vendor }}</text>
        <text x="{{ valueStartPosition }}" y="15" fill="#010101" fill-opacity=".3">{{ value }}</text>
        <text x="{{ valueStartPosition }}" y="14">{{ value }}</text>
    </g>
</svg>
EOF;
*/

private static $template  = <<<EOF
<svg xmlns="http://www.w3.org/2000/svg" width="{{ totalWidth }}" height="20">
    <linearGradient id="b" x2="0" y2="100%">
    <stop offset="0" stop-color="#bbb" stop-opacity=".1"/>
    <stop offset="1" stop-opacity=".1"/>
    </linearGradient>
    <mask id="a">
    <rect width="{{ totalWidth }}" height="20" rx="0" fill="#fff"/>
    </mask>
    <g mask="url(#a)">
    <rect width="{{ vendorWidth }}" height="20" fill="#555"/>
    <rect x="{{ vendorWidth }}" width="{{ valueWidth }}" height="20" fill="{{ valueColor }}"/>
    <rect width="{{ totalWidth }}" height="20" fill="url(#b)"/>
    </g>
    <g fill="#fff" text-anchor="middle" font-family="DejaVu Sans,Verdana,Geneva,sans-serif" font-size="11">
    <text x="{{ vendorStartPosition }}" y="15" fill="#010101" fill-opacity=".3">{{ vendor }}</text>
    <text x="{{ vendorStartPosition }}" y="14">{{ vendor }}</text>
    <text x="{{ valueStartPosition }}" y="15" fill="#010101" fill-opacity=".3">{{ value }}</text>
    <text x="{{ valueStartPosition }}" y="14">{{ value }}</text>
    </g>
</svg>
EOF;

    /**
     * Constructor.
     *
     * @param TextSizeCalculatorInterface $textSizeCalculator
     */
    public function __construct(TextSizeCalculatorInterface $textSizeCalculator = null)
    {
        $this->textSizeCalculator = $textSizeCalculator;

        if (null === $this->textSizeCalculator) {
            $this->textSizeCalculator = new GDTextSizeCalculator();
        }
    }

    /**
     * @param Badge $badge
     *
     * @return mixed
     */
    public function render(Badge $badge)
    {
        $parameters = array();

        $parameters['vendorWidth']         = $this->stringWidth($badge->getSubject());
        $parameters['valueWidth']          = $this->stringWidth($badge->getStatus());
        $parameters['totalWidth']          = $parameters['valueWidth'] + $parameters['vendorWidth'];
        $parameters['vendorColor']         = self::VENDOR_COLOR;
        $parameters['valueColor']          = $badge->getHexColor();
        $parameters['vendor']              = $badge->getSubject();
        $parameters['value']               = $badge->getStatus();
        $parameters['vendorStartPosition'] = round($parameters['vendorWidth'] / 2, 1) + 1;
        $parameters['valueStartPosition']  = $parameters['vendorWidth'] + round($parameters['valueWidth'] / 2, 1) - 1;

        return $this->renderSvg(self::$template, $parameters, $badge->getFormat());
    }

    /**
     * A list of all supported formats.
     *
     * @return array
     */
    public function supportedFormats()
    {
        return array('flat-square');
    }

    private function stringWidth($text)
    {
        return $this->textSizeCalculator->calculateWidth($text);
    }

    private function renderSvg($render, $parameters, $format)
    {
        foreach ($parameters as $key => $variable) {
            $render = str_replace(sprintf('{{ %s }}', $key), $variable, $render);
        }

        return Image::createFromString($render, $format);
    }
}
