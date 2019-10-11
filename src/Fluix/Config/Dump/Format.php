<?php

declare(strict_types = 1);

namespace Fluix\Config\Dump;

use Fluix\Config\Exception\InvalidArgumentException;

final class Format
{
    private const FORMATS = [".json", ".array.php", ".const.php", ".parameters.yml"];
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
        return new self(".array.php");
    }
    
    public static function const(): self
    {
        return new self(".const.php");
    }
    
    public static function yaml(): self
    {
        return new self(".parameters.yml");
    }
    
    public function __toString(): string
    {
        return $this->format;
    }
    
    public function equals($value): bool
    {
        if (is_string($value)) {
            return $this->format === $value;
        }
        
        if ($value instanceof self) {
            return $this->format === $value->format;
        }
        
        return false;
    }
    
    /** @return self[] */
    public static function all(): array
    {
        return array_map(
            function (string $format) {
                return new self($format);
            },
            self::FORMATS
        );
    }
    
    public function destination(string $basepath): string
    {
        return "{$basepath}{$this->format}";
    }
}
