<?php

declare(strict_types=1);

namespace PUGX\Poser\ValueObject;

use PUGX\Poser\Badge;
use Symfony\Component\Console\Input\InputInterface;

class InputRequest
{
    private string $subject;

    private string $status;

    private string $color;

    private string $style;

    private string $format;

    private function __construct(string $subject, string $status, string $color, string $style, string $format)
    {
        $this->subject = $subject;
        $this->status  = $status;
        $this->color   = $color;
        $this->style   = $style;
        $this->format  = $format;
    }

    public static function createFromInput(InputInterface $input): self
    {
        $subject = $input->getArgument('subject');
        $status  = $input->getArgument('status');
        $color   = $input->getArgument('color');
        $style   = $input->getOption('style');
        $format  = $input->getOption('format');

        if (!\is_string($subject)) {
            throw new \InvalidArgumentException('subject is not a string');
        }

        if (!\is_string($status)) {
            throw new \InvalidArgumentException('status is not a string');
        }

        if (!\is_string($color)) {
            throw new \InvalidArgumentException('color is not a string');
        }

        if (!\is_string($style)) {
            throw new \InvalidArgumentException('style is not a string');
        }

        if (!\is_string($format)) {
            $format = BADGE::DEFAULT_FORMAT;
        }

        return new self($subject, $status, $color, $style, $format);
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getColor(): string
    {
        return $this->color;
    }

    public function getStyle(): string
    {
        return $this->style;
    }

    public function getFormat(): string
    {
        return $this->format;
    }
}
