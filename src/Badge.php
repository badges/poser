<?php

namespace PUGX\Poser;

class Badge
{
    CONST DEFAULT_FORMAT = 'svg';
    private static $colorScheme = array(
        "brightgreen" => "44cc11",
        "green"       => "97CA00",
        "yellow"      => "dfb317",
        "yellowgreen" => "a4a61d",
        "orange"      => "fe7d37",
        "red"         => "e05d44",
        "blue"        => "007ec6",
        "grey"        => "555555",
        "lightgray"   => "9f9f9f"
    );

    private $subject;
    private $status;
    private $color;
    private $format;

    public function __construct($subject, $status, $color, $format = self::DEFAULT_FORMAT)
    {
        $this->subject = $this->escapeValue($subject);
        $this->status  = $this->escapeValue($status);
        $this->format  = $this->escapeValue($format);
        $this->color   = $this->getColorHex($color);

        if (!$this->isValidColorHex($this->color)) {
            throw new \InvalidArgumentException(sprintf('Color not valid %s', $this->color));
        }
    }

    /**
     * An array of the color names available.
     *
     * @return array
     */
    public static function getColorNamesAvailable()
    {
        return array_keys(self::$colorScheme);
    }

    /**
     * Factory method the creates a Badge from an URI
     * eg. I_m-liuggio-yellow.svg
     *
     * @param string $URI
     *
     * @return Badge
     * @throws \InvalidArgumentException
     */
    public static function fromURI($URI)
    {
        $regex = '/^(([^-]|--)+)-(([^-]|--)+)-(([^-]|--)+)\.(svg|png|gif|jpg)$/';
        $match = array();

        if (1 != preg_match($regex, $URI, $match) && (7 != count($match))) {
            throw new \InvalidArgumentException('The URI given is not a valid URI'.$URI);
        }

        $subject = $match[1];
        $status  = $match[3];
        $color   = $match[5];
        $format  = $match[7];

        return new self($subject, $status, $color, $format);
    }

    /**
     * @return string the Hexadecimal #FFFFFF.
     */
    public function getHexColor()
    {
        return '#'.$this->color;
    }

    /**
     * @return string the format of the image eg. `svg`.
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    public function __toString()
    {
        return sprintf("%s-%s-%s.%s",
            $this->subject,
            $this->status,
            $this->color,
            $this->format
        );
    }

    private function escapeValue($value)
    {
        $pattern = array(
            // '/([^_])_([^_])/g', // damn it global doesn't work in PHP
            '/([^_])_$/',
            '/^_([^_])/',
            '/__/',
            '/--+/',
        );
        $replacement = array(
            //'$1 $2',
            '$1 ',
            ' $1',
            '°§*¼',
            '-',
        );
        $ret = preg_replace($pattern, $replacement, $value);
        $ret = str_replace('_', ' ', $ret);    // this fix the php pgrep_replace is not global :(
        $ret = str_replace('°§*¼', '_', $ret); // this fix the php pgrep_replace is not global :(

        return $ret;
    }

    private function getColorHex($color)
    {
        return array_key_exists($color, self::$colorScheme) ? self::$colorScheme[$color] : $color;
    }

    private function isValidColorHex($color)
    {
        $color = ltrim($color, "#");
        $regex = '/^[0-9a-fA-F]{6}$/';

        return preg_match($regex, $color);
    }
}
