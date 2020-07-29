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
 * Class Image, an Image value Object.
 *
 * @author Claudio D'Alicandro <claudio.dalicandro@gmail.com>
 * @author Giulio De Donato <liuggio@gmail.com>
 */
class Image
{
    private string $content;

    private string $style;

    private function __construct(string $content, string $style)
    {
        $this->content = $content;
        $this->style   = $style;
    }

    /**
     * Returns the image content as binary string.
     */
    public function __toString(): string
    {
        return (string) $this->content;
    }

    /**
     * Factory method.
     */
    public static function createFromString(string $content, string $style): self
    {
        return new self($content, $style);
    }

    public function getStyle(): string
    {
        return $this->style;
    }
}
