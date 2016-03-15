<?php

namespace PUGX\Poser;

use PUGX\Poser\Render\RenderInterface;

class Poser
{
    private $renders;

    /**
     * Constructor.
     *
     * @param $renders
     */
    public function __construct($renders)
    {
        $this->renders = array();
        if (!is_array($renders)) {
            $renders = array($renders);
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
     *
     * @return Image
     */
    public function generate($subject, $status, $color, $format)
    {
        $badge = new Badge($subject, $status, $color, $format);

        return $this->getRenderFor($badge->getFormat())->render($badge);
    }

    /**
     * Generate and Render a badge according to the format from an URI,
     * eg license-MIT-blue.svg or I_m-liuggio-yellow.svg.
     *
     * @param $string
     * @return Image
     */
    public function generateFromURI($string)
    {
        $badge = Badge::fromURI($string);

        return $this->getRenderFor($badge->getFormat())->render($badge);
    }

    /**
     * All the formats available.
     *
     * @return array
     */
    public function validFormats()
    {
        return array_keys($this->renders);
    }

    private function addFormatRender(RenderInterface $render)
    {
        foreach ($render->supportedFormats() as $format) {
            $this->renders[$format] = $render;
        }
    }

    /**
     * @param $format
     *
     * @return RenderInterface
     */
    private function getRenderFor($format)
    {
        if (!isset($this->renders[$format])) {
            throw new \InvalidArgumentException(sprintf('No render founds for this format [%s]', $format));
        }

        return $this->renders[$format];
    }
}
