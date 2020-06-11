<?php

namespace PUGX\Poser;

class Badge
{
    public const DEFAULT_FORMAT = 'svg';

    private static array $colorScheme = [
        'brightgreen' => '44cc11',
        'green'       => '97CA00',
        'yellow'      => 'dfb317',
        'yellowgreen' => 'a4a61d',
        'orange'      => 'fe7d37',
        'red'         => 'e05d44',
        'blue'        => '007ec6',
        'grey'        => '555555',
        'lightgray'   => '9f9f9f',
    ];

    private string $subject;
    private string $status;
    private string $color;
    private string $format;

    public function __construct(string $subject, string $status, string $color, string $format = self::DEFAULT_FORMAT)
    {
        $this->subject = $this->escapeValue($subject);
        $this->status  = $this->escapeValue($status);
        $this->format  = $this->escapeValue($format);
        $this->color   = $this->getColorHex($color);

        if (!$this->isValidColorHex($this->color)) {
            throw new \InvalidArgumentException(\sprintf('Color not valid %s', $this->color));
        }
    }

    /**
     * An array of the color names available.
     */
    public static function getColorNamesAvailable(): array
    {
        return \array_keys(self::$colorScheme);
    }

    /**
     * Factory method the creates a Badge from an URI
     * eg. I_m-liuggio-yellow.svg.
     *
     * @throws \InvalidArgumentException
     */
    public static function fromURI(string $URI): self
    {
        $regex = '/^(([^-]|--)+)-(([^-]|--)+)-(([^-]|--)+)\.(svg|png|gif|jpg)$/';
        $match = [];

        if (1 !== \preg_match($regex, $URI, $match) && (7 !== \count($match))) {
            throw new \InvalidArgumentException('The URI given is not a valid URI' . $URI);
        }

        $subject = $match[1];
        $status  = $match[3];
        $color   = $match[5];
        $format  = $match[7];

        return new self($subject, $status, $color, $format);
    }

    /**
     * @return string the Hexadecimal #FFFFFF
     */
    public function getHexColor(): string
    {
        return '#' . $this->color;
    }

    /**
     * @return string the format of the image eg. `svg`.
     */
    public function getFormat(): string
    {
        return $this->format;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function __toString(): string
    {
        return \sprintf(
            '%s-%s-%s.%s',
            $this->subject,
            $this->status,
            $this->color,
            $this->format
        );
    }

    private function escapeValue(string $value): string
    {
        $pattern = [
            // '/([^_])_([^_])/g', // damn it global doesn't work in PHP
            '/([^_])_$/',
            '/^_([^_])/',
            '/__/',
            '/--+/',
        ];
        $replacement = [
            //'$1 $2',
            '$1 ',
            ' $1',
            '°§*¼',
            '-',
        ];
        $ret = \preg_replace($pattern, $replacement, $value);
        $ret = \str_replace('_', ' ', $ret);    // this fix the php pgrep_replace is not global :(
        $ret = \str_replace('°§*¼', '_', $ret); // this fix the php pgrep_replace is not global :(

        return $ret;
    }

    private function getColorHex(string $color): string
    {
        return \array_key_exists($color, self::$colorScheme) ? self::$colorScheme[$color] : $color;
    }

    /**
     * @return false|int
     */
    private function isValidColorHex(string $color)
    {
        $color = \ltrim($color, '#');
        $regex = '/^[0-9a-fA-F]{6}$/';

        return \preg_match($regex, $color);
    }
}
