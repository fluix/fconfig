<?php

declare(strict_types = 1);

namespace Fluix\Config;

final class Source
{
    private File $source;
    
    private function __construct(File $source)
    {
        $this->source = $source;
    }
    
    public static function fromPath(string $path): self
    {
        return new self(File::fromPath($path));
    }
    
    public function source(): File
    {
        return $this->source;
    }
    
    public function __toString(): string
    {
        return (string)$this->source;
    }
}
