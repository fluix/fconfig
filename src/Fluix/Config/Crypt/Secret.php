<?php

declare(strict_types = 1);

namespace Fluix\Config\Crypt;

use Fluix\Config\Exception\InvalidArgumentException;

final class Secret
{
    private string $value;
    
    private function __construct(string $value)
    {
        if (16 !== \strlen($value)) {
            throw new InvalidArgumentException("Secret must be 16 characters long");
        }
        
        $this->value = $value;
    }
    
    public static function fromString(string $secret): self
    {
        return new self($secret);
    }
    
    public function __toString(): string
    {
        return $this->value;
    }
}
