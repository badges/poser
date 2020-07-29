<?php

namespace PUGX\Poser;

use PUGX\Poser\Render\RenderInterface;

class Poser
{
    private array $renders;

    /**
     * Constructor.
     *
     * @param $renders
     */
    public function __construct($renders)
    {
        $this->renders = [];
        if (!\is_array($renders)) {
            $renders = [$renders];
        }

        foreach ($renders as $render) {
            $this->addStyleRender($render);
        }
    }

    /**
     * Generate and Render a badge according to the style.
     *
     * @param $subject
     * @param $status
     * @param $color
     * @param $style
     * @param $format
     */
    public function generate(string $subject, string $status, string $color, string $style, string $format = Badge::DEFAULT_FORMAT): Image
    {
        $badge = new Badge($subject, $status, $color, $style, $format);

        return $this->getRenderFor($badge->getStyle())->render($badge);
    }

    /**
     * Generate and Render a badge according to the format from an URI,
     * eg license-MIT-blue.svg or I_m-liuggio-yellow.svg.
     *
     * @param $string
     */
    public function generateFromURI(string $string): Image
    {
        $badge = Badge::fromURI($string);

        return $this->getRenderFor($badge->getStyle())->render($badge);
    }

    /**
     * All the styles available.
     */
    public function validStyles(): array
    {
        return \array_keys($this->renders);
    }

    private function addStyleRender(RenderInterface $render): void
    {
        $this->renders[$render->getBadgeStyle()] = $render;
    }

    private function getRenderFor(string $style): RenderInterface
    {
        if (!isset($this->renders[$style])) {
            throw new \InvalidArgumentException(\sprintf('No render founds for this style [%s]', $style));
        }

        return $this->renders[$style];
    }
}
