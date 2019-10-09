<?php

declare(strict_types = 1);

namespace Fluix\Config\Dump;

use Fluix\Config\Exception\InvalidArgumentException;

final class Format
{
    private const FORMATS = [".json", ".php", ".const.php"];
    private $format;
    
    private function __construct(string $format)
    {
        if (!in_array($format, self::FORMATS)) {
            throw new InvalidArgumentException("Invalid format {$format}. Supported formats:" . implode(", ", self::FORMATS));
        }
        $this->format = $format;
    }
    
    public static function json(): self
    {
        return new self(".json");
    }
    
    public static function php(): self
    {
        return new self(".php");
    }
    
    public static function const(): self
    {
        return new self(".const.php");
    }
    
    public function __toString(): string
    {
        return $this->format;
    }
    
    public function destination(string $basepath): string
    {
        return "{$basepath}{$this->format}";
    }
}
