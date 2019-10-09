<?php

declare(strict_types = 1);

namespace Fluix\Config\Dump;

use Fluix\Config\Exception\InvalidArgumentException;

final class Format
{
    private const FORMATS = ["json", "php", "const"];
    private $format;
    
    private function __construct(string $format)
    {
        if (!in_array($format, self::FORMATS)) {
            throw new InvalidArgumentException("Invalid format {$format}. Supported formats:" . implode(", ", self::FORMATS));
        }
        $this->format = $format;
    }
    
    public static function fromString(string $format): self
    {
        return new self($format);
    }
    
    public function __toString(): string
    {
        return $this->format;
    }
}
