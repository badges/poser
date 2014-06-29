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

/**
 * Class SvgGenerator
 *
 * @author Claudio D'Alicandro <claudio.dalicandro@gmail.com>
 * @author Giulio De Donato <liuggio@gmail.com>
 */
class SvgRender implements RenderInterface
{
    const VENDOR_COLOR            = '#555';

    private $textSizeCalculator;
    private static $template = '<svg xmlns="http://www.w3.org/2000/svg" width="{{ totalWidth }}" height="18">
    <linearGradient id="smooth" x2="0" y2="100%">
        <stop offset="0"  stop-color="#fff" stop-opacity=".7"/>
        <stop offset=".1" stop-color="#aaa" stop-opacity=".1"/>
        <stop offset=".9" stop-color="#000" stop-opacity=".3"/>
        <stop offset="1"  stop-color="#000" stop-opacity=".5"/>
    </linearGradient>
    <rect rx="4" width="{{ totalWidth }}" height="18" fill="{{ vendorColor }}"/>
    <rect rx="4" x="{{ vendorWidth }}" width="{{ valueWidth }}" height="18" fill="{{ valueColor }}"/>
    <rect x="{{ vendorWidth }}" width="4" height="18" fill="{{ valueColor }}"/>
    <rect rx="4" width="{{ totalWidth }}" height="18" fill="url(#smooth)"/>
    <g fill="#fff" text-anchor="middle" font-family="DejaVu Sans,Verdana,Geneva,sans-serif" font-size="11">
        <text x="{{ vendorStartPosition }}" y="13" fill="#010101" fill-opacity=".3">{{ vendor }}</text>
        <text x="{{ vendorStartPosition }}" y="12">{{ vendor }}</text>
        <text x="{{ valueStartPosition }}" y="13" fill="#010101" fill-opacity=".3">{{ value }}</text>
        <text x="{{ valueStartPosition }}" y="12">{{ value }}</text>
    </g>
</svg>';

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

        return $this->renderSvg(self::$template, $parameters);
    }

    /**
     * Render a badge.
     *
     * @param Badge $badge
     *
     * @return string
     */
    public function supportedFormats()
    {
        return array('svg');
    }

    private function stringWidth($text)
    {
        return $this->textSizeCalculator->calculateWidth($text);
    }

    private function renderSvg($render, $parameters)
    {
        foreach ($parameters as $key => $variable) {
            $render = str_replace(sprintf('{{ %s }}', $key), $variable, $render);
        }

        return $render;
    }
}
