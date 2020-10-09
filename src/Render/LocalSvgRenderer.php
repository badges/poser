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
use SimpleXMLElement;

/**
 * Local SVG renderer.
 * Generate SVG badges from local templates.
 *
 * @author JM Leroux <jmleroux.pro@gmail.com>
 */
abstract class LocalSvgRenderer implements RenderInterface
{
    public const VENDOR_COLOR = '#555';

    private ?TextSizeCalculatorInterface $textSizeCalculator = null;

    private ?string $templatesDirectory = null;

    public function __construct(?TextSizeCalculatorInterface $textSizeCalculator = null, ?string $templatesDirectory = null)
    {
        $this->textSizeCalculator = $textSizeCalculator;
        if (null === $this->textSizeCalculator) {
            $this->textSizeCalculator = new GDTextSizeCalculator();
        }

        $this->templatesDirectory = $templatesDirectory;
        if (null === $this->templatesDirectory) {
            $this->templatesDirectory = __DIR__ . '/../Resources/templates';
        }
    }

    public function render(Badge $badge): Image
    {
        $template   = $this->getTemplate($this->getTemplateName());
        $parameters = $this->buildParameters($badge);

        return $this->renderSvg($template, $parameters, $badge->getStyle());
    }

    abstract protected function getTemplateName(): string;

    /**
     * @return string SVG content of the template
     */
    private function getTemplate(string $style): string
    {
        if (null === $this->templatesDirectory) {
            throw new \InvalidArgumentException('TemplateDirectory cannot be null');
        }

        $filepath = \sprintf('%s/%s.svg', $this->templatesDirectory, $style);

        if (!\file_exists($filepath)) {
            throw new \InvalidArgumentException(\sprintf('No template for style %s', $style));
        }

        return \file_get_contents($filepath);
    }

    private function stringWidth(string $text): float
    {
        if (null === $this->textSizeCalculator) {
            throw new \InvalidArgumentException('TextSizeCalculator cannot be null');
        }

        return $this->textSizeCalculator->calculateWidth($text);
    }

    private function renderSvg(string $render, array $parameters, string $style): Image
    {
        foreach ($parameters as $key => $variable) {
            $render = \str_replace(\sprintf('{{ %s }}', $key), $variable, $render);
        }

        try {
            $xml = new SimpleXMLElement($render);
        } catch (\Exception $e) {
            throw new \RuntimeException('Generated string is not a valid XML');
        }
        if ('svg' !== $xml->getName()) {
            throw new \RuntimeException('Generated xml is not a SVG');
        }

        return Image::createFromString($render, $style);
    }

    private function buildParameters(Badge $badge): array
    {
        $parameters = [];

        $parameters['vendorWidth']         = $this->stringWidth($badge->getSubject());
        $parameters['valueWidth']          = $this->stringWidth($badge->getStatus());
        $parameters['totalWidth']          = $parameters['valueWidth'] + $parameters['vendorWidth'];
        $parameters['vendorColor']         = static::VENDOR_COLOR;
        $parameters['valueColor']          = $badge->getHexColor();
        $parameters['vendor']              = $badge->getSubject();
        $parameters['value']               = $badge->getStatus();
        $parameters['vendorStartPosition'] = \round($parameters['vendorWidth'] / 2, 1) + 1;
        $parameters['valueStartPosition']  = $parameters['vendorWidth'] + \round($parameters['valueWidth'] / 2, 1) - 1;

        return $parameters;
    }
}
