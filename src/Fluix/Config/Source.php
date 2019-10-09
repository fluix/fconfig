<?php

declare(strict_types = 1);

namespace Fluix\Config;

final class Source
{
    private $source;
    private $section;
    
    private function __construct(File $source, string $section = "")
    {
        $this->source = $source;
        $this->section = $section;
    }
    
    public static function fromPath(string $path, string $section = ""): self
    {
        return new self(File::fromPath($path), $section);
    }
    
    public function source(): File
    {
        return $this->source;
    }
    
    public function section(): string
    {
        return $this->section;
    }
    
    public function __toString(): string
    {
        return (string)$this->source;
    }
}
