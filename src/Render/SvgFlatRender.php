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
 * Class SvgFlatGenerator.
 *
 * @author Giulio De Donato <liuggio@gmail.com>
 */
class SvgFlatRender extends LocalSvgRenderer
{
    /**
     * A list of all supported formats.
     *
     * @return array|string[]
     */
    public function supportedFormats(): array
    {
        return ['flat'];
    }

    protected function getTemplateName(): string
    {
        return 'flat';
    }
}
