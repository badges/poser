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
readonly class Image
{
    private function __construct(private string $content, private string $style)
    {
    }

    /**
     * Returns the image content as binary string.
     */
    public function __toString(): string
    {
        return $this->content;
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
