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

/**
 * Class SvgGenerator
 *
 * @author Claudio D'Alicandro <claudio.dalicandro@gmail.com>
 * @author Giulio De Donato <liuggio@gmail.com>
 */
class SvgRender extends LocalSvgRenderer
{
    /**
     * A list of all supported formats.
     *
     * @return array
     */
    public function supportedFormats()
    {
        return array('plastic');
    }

    protected function getTemplateName()
    {
        return 'plastic';
    }
}
