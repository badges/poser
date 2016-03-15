<?php

namespace spec\PUGX\Poser;

use PhpSpec\Exception\Exception;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use PUGX\Poser\Badge;

class BadgeSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->beConstructedWith('a', 'b', '97CA00', 'svg');
        $this->shouldHaveType('Pugx\Poser\Badge');
    }

    function it_should_be_constructed_by_fromURI_factory_method()
    {
        $this->beConstructedWith('a', 'b', '97CA00', 'svg');
        $assert = 'version-stable-97CA00.svg';
        $it = Badge::fromURI($assert);

        if ((string) $it !== $assert) {
            throw new Exception(sprintf("from[%s] having[%s]\n", $assert, (string) $it));
        }
    }

    function it_should_be_constructed_by_fromURI_factory_method_escaping_correctly_underscores()
    {
        $this->beConstructedWith('a', 'b', '97CA00', 'svg');
        $input = 'I__m__liugg__io-b-97CA00.svg';
        $assertInput = 'I_m_liugg_io-b-97CA00.svg';
        $it = Badge::fromURI($input);

        if ((string) $it !== $assertInput) {
            throw new Exception(sprintf("from[%s] wants[%s] having[%s]\n", $input, $assertInput, (string) $it));
        }
    }

    function it_should_be_constructed_by_fromURI_factory_method_escaping_correctly_with_single_underscore()
    {
        $this->beConstructedWith('a', 'b', '97CA00', 'svg');
        $input = 'I_m_liuggio-b-97CA00.svg';
        $assertInput = 'I m liuggio-b-97CA00.svg';
        $it = Badge::fromURI($input);

        if ((string) $it !== $assertInput) {
            throw new Exception(sprintf("from[%s] wants[%s] having[%s]\n", $input, $assertInput, (string) $it));
        }
    }

    function it_should_be_constructed_by_fromURI_factory_method_escaping_correctly_with_dashes()
    {
        $this->beConstructedWith('a', 'b', '97CA00', 'svg');
        $input = 'I--m--liuggio-b-97CA00.svg';
        $assertInput = 'I-m-liuggio-b-97CA00.svg';
        $it = Badge::fromURI($input);

        if ((string) $it !== $assertInput) {
            throw new Exception(sprintf("from[%s] wants[%s] having[%s]\n", $input, $assertInput, (string) $it));
        }
    }

    /**
     * @dataProvider positiveConversionExamples
     */
    function it_should_validate_available_color_schemes($colorName, $expectedValue)
    {
        $this->beConstructedWith('a', 'b', $colorName, 'svg');
        $this->getHexColor()->shouldBeString();
    }

    public function positiveConversionExamples()
    {
        $colorNames = Badge::getColorNamesAvailable();

        $data = array();
        foreach ($colorNames as $colorName) {
            $data[] = array($colorName, null);
        }

        return $data;
    }
}
