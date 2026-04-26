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
    public const VENDOR_COLOR = '#555';

    /**
     * Horizontal padding on each side of the text (px).
     */
    protected const PADDING_H = 2;

    /**
     * Gap between logo right edge and text start (px).
     */
    protected const LOGO_TEXT_GAP = 3;

    /**
     * Logo size (px).
     */
    protected const LOGO_WIDTH = 14;

    /**
     * Logo vertical offset for 20px-tall badges.
     * Override in subclasses for different badge heights.
     */
    protected function logoY(): int
    {
        return 3;
    }

    private TextSizeCalculatorInterface $textSizeCalculator;
    private string $templatesDirectory;

    public function __construct(
        ?TextSizeCalculatorInterface $textSizeCalculator = null,
        ?string $templatesDirectory = null
    ) {
        $this->textSizeCalculator = $textSizeCalculator ?? new GDTextSizeCalculator();
        $this->templatesDirectory = $templatesDirectory ?? (__DIR__ . '/../Resources/templates');
    }

    public function render(Badge $badge): Image
    {
        $template   = $this->getTemplate($this->getTemplateName());
        $parameters = $this->buildParameters($badge);

        return $this->renderSvg($template, $parameters, $badge->getStyle());
    }

    abstract protected function getTemplateName(): string;

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

    protected function stringWidth(string $text): float
    {
        return $this->textSizeCalculator->calculateWidth($text);
    }

    private function renderSvg(string $render, array $parameters, string $style): Image
    {
        foreach ($parameters as $key => $variable) {
            $render = \str_replace(\sprintf('{{ %s }}', $key), (string) $variable, $render);
        }

        $render = preg_replace('/\s+/', ' ', $render);
        $render = str_replace('> <', '><', $render);

        try {
            $xml = new \SimpleXMLElement($render);
        } catch (\Exception $e) {
            throw new \RuntimeException('Generated string is not a valid XML: ' . $e->getMessage());
        }

        if ('svg' !== $xml->getName()) {
            throw new \RuntimeException('Generated xml is not a SVG');
        }

        return Image::createFromString($render, $style);
    }

    protected function buildParameters(Badge $badge): array
    {
        $hasLogo    = (bool) $badge->getLogo();
        $logoOffset = $hasLogo ? (self::LOGO_WIDTH + self::LOGO_TEXT_GAP) : 0;

        $subject = $badge->getSubject();
        $status  = $badge->getStatus();

        $subjectW = (int) round($this->stringWidth($subject));
        $statusW  = (int) round($this->stringWidth($status));
        $vendorWidth = self::PADDING_H + $logoOffset + $subjectW + self::PADDING_H;
        $valueWidth  = self::PADDING_H + $statusW + self::PADDING_H;
        $totalWidth = $vendorWidth + $valueWidth;
        $vendorCenter = $logoOffset + ($subjectW / 2) + self::PADDING_H;
        $valueCenter = ($statusW / 2) + self::PADDING_H;
        $vendorStartX = (int) round($vendorCenter * 10);
        $valueStartX  = (int) round(($vendorWidth + $valueCenter) * 10);
        $vendorTextLen = $subjectW * 10;
        $valueTextLen  = $statusW * 10;

        return [
            'vendorWidth'       => $vendorWidth,
            'valueWidth'        => $valueWidth,
            'totalWidth'        => $totalWidth,
            'vendorColor'       => $badge->getLabelColor() ?: static::VENDOR_COLOR,
            'valueColor'        => $badge->getHexColor(),
            'vendor'            => $subject,
            'value'             => $status,
            'vendorUpper'       => strtoupper($subject),
            'valueUpper'        => strtoupper($status),
            'vendorStartX'      => $vendorStartX,
            'valueStartX'       => $valueStartX,
            'vendorTextLength'  => $vendorTextLen,
            'valueTextLength'   => $valueTextLen,
            'vendorWidthMinus1' => $vendorWidth - 1,
            'valueWidthMinus1'  => $valueWidth - 1,
            'valueRectX'        => $vendorWidth + 6 + 0.5,
            'separatorX'        => $vendorWidth + 0.5,
            'logoElement'       => $hasLogo ? $this->buildLogoElement($badge) : '',
        ];
    }

    private function buildLogoElement(Badge $badge): string
    {
        $y         = $this->logoY();
        $logoColor = $badge->getLogoColor() ?: '#fff';
        $logo      = $badge->getLogo();
 
        if (strpos($logo, 'data:image/svg+xml;base64,') === 0) {
            $b64  = preg_replace('/\s+/', '', substr($logo, strlen('data:image/svg+xml;base64,')));
            $href = 'data:image/svg+xml;base64,' . $b64;
            return sprintf('<image x="5" y="%d" width="14" height="14" href="%s"/>', $y, $href);
        }
 
        if (strpos($logo, 'data:image/') === 0) {
            $href = str_replace(' ', '+', $logo);
            return sprintf('<image x="5" y="%d" width="14" height="14" href="%s"/>', $y, htmlspecialchars($href));
        }
 
        if (strpos($logo, 'http') === 0) {
            return sprintf('<image x="5" y="%d" width="14" height="14" href="%s"/>', $y, htmlspecialchars($logo));
        }

        $svg  = sprintf(
            '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="%s" d="%s"/></svg>',
            $logoColor,
            $logo
        );

        $href = 'data:image/svg+xml;base64,' . base64_encode($svg);
        
        return sprintf('<image x="5" y="%d" width="14" height="14" href="%s"/>', $y, $href);
    }
}
