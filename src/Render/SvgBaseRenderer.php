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
 * Base class for simple SVG renderers that follow the standard pattern.
 *
 * @author Refactored for better maintainability
 */
abstract class SvgBaseRenderer extends LocalSvgRenderer
{
    /**
     * Get the template name which should match the badge style.
     */
    protected function getTemplateName(): string
    {
        return $this->getBadgeStyle();
    }
}
