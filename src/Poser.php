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
            $this->addFormatRender($render);
        }
    }

    /**
     * Generate and Render a badge according to the format.
     *
     * @param $subject
     * @param $status
     * @param $color
     * @param $format
     */
    public function generate(string $subject, string $status, string $color, string $format): Image
    {
        $badge = new Badge($subject, $status, $color, $format);

        return $this->getRenderFor($badge->getFormat())->render($badge);
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

        return $this->getRenderFor($badge->getFormat())->render($badge);
    }

    /**
     * All the formats available.
     */
    public function validFormats(): array
    {
        return \array_keys($this->renders);
    }

    private function addFormatRender(RenderInterface $render): void
    {
        foreach ($render->supportedFormats() as $format) {
            $this->renders[$format] = $render;
        }
    }

    private function getRenderFor(string $format): RenderInterface
    {
        if (!isset($this->renders[$format])) {
            throw new \InvalidArgumentException(\sprintf('No render founds for this format [%s]', $format));
        }

        return $this->renders[$format];
    }
}
