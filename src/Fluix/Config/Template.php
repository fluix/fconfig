<?php

declare(strict_types = 1);

namespace Fluix\Config;

final class Template
{
    private File $template;
    
    private function __construct(File $template)
    {
        $this->template = $template;
    }
    
    public static function fromPath(string $path): self
    {
        return new self(File::fromPath($path));
    }
    
    public function template(): File
    {
        return $this->template;
    }
    
    public function __toString(): string
    {
        return (string)$this->template;
    }
}
