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
use PUGX\Poser\Calculator\GDTextSizeCalculator;
use PUGX\Poser\Calculator\TextSizeCalculatorInterface;
use PUGX\Poser\Image;

/**
 * Local SVG renderer.
 * Generate SVG badges from local templates.
 *
 * @author JM Leroux <jmleroux.pro@gmail.com>
 */
abstract class LocalSvgRenderer implements RenderInterface
{
    const VENDOR_COLOR = '#555';

    /**
     * @var TextSizeCalculatorInterface
     */
    private $textSizeCalculator;

    /**
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
        $template   = $this->getTemplate($this->getTemplateName());
        $parameters = $this->buildParameters($badge);

        return $this->renderSvg($template, $parameters, $badge->getFormat());
    }

    /**
     * @return string
     */
    abstract protected function getTemplateName();

    /**
     * @param $format
     *
     * @return string SVG content of the template
     */
    private function getTemplate($format)
    {
        $templatesDirectory = __DIR__ . '/../Resources/templates';
        $filepath           = sprintf('%s/%s.svg', $templatesDirectory, $format);

        if (!file_exists($filepath)) {
            throw new \InvalidArgumentException(sprintf('No template for format %s', $format));
        }

        return file_get_contents($filepath);
    }

    /**
     * @param $text
     *
     * @return float
     */
    private function stringWidth($text)
    {
        return $this->textSizeCalculator->calculateWidth($text);
    }

    /**
     * @param string $render
     * @param array  $parameters
     * @param string $format
     *
     * @return Image
     */
    private function renderSvg($render, $parameters, $format)
    {
        foreach ($parameters as $key => $variable) {
            $render = str_replace(sprintf('{{ %s }}', $key), $variable, $render);
        }

        // validate svg
        libxml_use_internal_errors(true);
        $xml = simplexml_load_string($render);
        if (false === $xml) {
            throw new \RuntimeException('Generated string is not a valid SVG');
        }

        return Image::createFromString($render, $format);
    }

    /**
     * @param Badge $badge
     *
     * @return array
     */
    private function buildParameters(Badge $badge)
    {
        $parameters = array();

        $parameters['vendorWidth']         = $this->stringWidth($badge->getSubject());
        $parameters['valueWidth']          = $this->stringWidth($badge->getStatus());
        $parameters['totalWidth']          = $parameters['valueWidth'] + $parameters['vendorWidth'];
        $parameters['vendorColor']         = static::VENDOR_COLOR;
        $parameters['valueColor']          = $badge->getHexColor();
        $parameters['vendor']              = $badge->getSubject();
        $parameters['value']               = $badge->getStatus();
        $parameters['vendorStartPosition'] = round($parameters['vendorWidth'] / 2, 1) + 1;
        $parameters['valueStartPosition']  = $parameters['vendorWidth'] + round($parameters['valueWidth'] / 2, 1) - 1;

        return $parameters;
    }
}
