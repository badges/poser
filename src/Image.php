<?php

/*
 * This file is part of the badge-poser package.
 *
 * (c) PUGX <http://pugx.github.io/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PUGX\Poser;

/**
 * Class Image, an Image value Object
 *
 * @author Claudio D'Alicandro <claudio.dalicandro@gmail.com>
 * @author Giulio De Donato <liuggio@gmail.com>
 */
class Image
{
    /**
     * @var string $content
     */
    private $content;
    /**
     * @var string $format
     */
    private $format;

    /**
     * @param string $content
     * @param string $format
     */
    private function __construct($content, $format)
    {
        $this->content = $content;
        $this->format = $format;
    }

    /**
     * Returns the image content as binary string
     */
    public function __toString()
    {
        return (string) $this->content;
    }

    /**
     * Factory method
     *
     * @param string $content
     * @param string $format
     *
     * @return Image
     */
    public static function createFromString($content, $format)
    {
        return new self($content, $format);
    }

    /**
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

}
