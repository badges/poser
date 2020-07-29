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

/**
 * Class SvgFlatSquareRender.
 *
 * @author Giulio De Donato <liuggio@gmail.com>
 */
class SvgFlatSquareRender extends LocalSvgRenderer
{
    public function getBadgeStyle(): string
    {
        return 'flat-square';
    }

    protected function getTemplateName(): string
    {
        return $this->getBadgeStyle();
    }
}
