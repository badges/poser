<?php

namespace PUGX\Poser;

class Badge
{
    public const DEFAULT_STYLE  = 'flat';
    public const DEFAULT_FORMAT = 'svg';

    private static array $colorScheme = [
        'brightgreen'   => '44cc11',
        'green'         => '97ca00',
        'yellowgreen'   => 'a4a61d',
        'yellow'        => 'dfb317',
        'orange'        => 'fe7d37',
        'red'           => 'e05d44',
        'blue'          => '007ec6',
        'lightgray'     => '9f9f9f',
        'lightgrey'     => '9f9f9f',
        'gray'          => '555555',
        'grey'          => '555555',
        'blueviolet'    => '8a2be2',
        'success'       => '97ca00',
        'important'     => 'fe7d37',
        'critical'      => 'e05d44',
        'informational' => '007ec6',
        'inactive'      => '9f9f9f',
    ];

    private string $subject;
    private string $status;
    private string $color;
    private string $style;
    private string $format;
    private ?string $labelColor;
    private ?string $logo;
    private ?string $logoColor;

    public function __construct(string $subject, string $status, string $color, string $style = self::DEFAULT_STYLE, string $format = self::DEFAULT_FORMAT, ?string $labelColor = null, ?string $logo = null, ?string $logoColor = null)
    {
        $this->subject    = $this->escapeValue($subject);
        $this->status     = $this->escapeValue($status);
        $this->color      = $this->getColorHex($color);
        $this->style      = $this->escapeValue($style);
        $this->format     = $this->escapeValue($format);
        $this->labelColor = $labelColor ? $this->getColorHex($labelColor) : null;
        $this->logo       = $logo ? $this->escapeValue($logo) : null;
        $this->logoColor  = $logoColor ? $this->getColorHex($logoColor) : null;

        if (!$this->isValidColorHex($this->color)) {
            throw new \InvalidArgumentException(\sprintf('Color not valid %s', $this->color));
        }
        if ($this->labelColor && !$this->isValidColorHex($this->labelColor)) {
            throw new \InvalidArgumentException(\sprintf('Label color not valid %s', $this->labelColor));
        }
        if ($this->logoColor && !$this->isValidColorHex($this->logoColor)) {
            throw new \InvalidArgumentException(\sprintf('Logo color not valid %s', $this->logoColor));
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
        $parsedURI = \parse_url($URI);

        if (!isset($parsedURI['path'])) {
            throw new \InvalidArgumentException('The URI given is not a valid URI' . $URI);
        }

        $path      = $parsedURI['path'];
        \parse_str($parsedURI['query'] ?? '', $query);

        $regex = '/^(([^-]|--)+)-(([^-]|--)+)-(([^-.]|--)+)(\.(svg|png|gif|jpg))?$/';
        $match = [];

        if (1 !== \preg_match($regex, $path, $match) && (6 > \count($match))) {
            throw new \InvalidArgumentException('The URI given is not a valid URI' . $URI);
        }
        $subject    = $match[1];
        $status     = $match[3];
        $color      = $match[5];
        $style      = isset($query['style']) && '' !== $query['style'] ? $query['style'] : self::DEFAULT_STYLE;
        $format     = $match[8] ?? self::DEFAULT_FORMAT;
        $labelColor = $query['labelColor'] ?? null;
        $logo       = $query['logo'] ?? null;
        $logoColor  = $query['logoColor'] ?? null;

        return new self($subject, $status, $color, $style, $format, $labelColor, $logo, $logoColor);
    }

    /**
     * @return string the Hexadecimal #FFFFFF
     */
    public function getHexColor(): string
    {
        return '#' . $this->color;
    }

    /**
     * @return string the style of the image eg. `flat`.
     */
    public function getStyle(): string
    {
        return $this->style;
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

    public function getLabelColor(): ?string
    {
        return $this->labelColor ? '#' . $this->labelColor : null;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function getLogoColor(): ?string
    {
        return $this->logoColor ? '#' . $this->logoColor : null;
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
            // '$1 $2',
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
        $color = \array_key_exists($color, self::$colorScheme) ? self::$colorScheme[$color] : $color;
        $color = \ltrim($color, '#');

        // Convert 3-digit hex to 6-digit hex
        if (3 === \strlen($color)) {
            $r     = $color[0] . $color[0];
            $g     = $color[1] . $color[1];
            $b     = $color[2] . $color[2];
            $color = $r . $g . $b;
        }

        return $color;
    }

    /**
     * @return false|int
     */
    private function isValidColorHex(string $color)
    {
        $color = \ltrim($color, '#');
        $regex = '/^[0-9a-fA-F]{3}$|^[0-9a-fA-F]{6}$/';

        return \preg_match($regex, $color);
    }
}
