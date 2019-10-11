<?php

declare(strict_types = 1);

namespace Fluix\Config\Dump;

use Fluix\Config\File;

final class Destination
{
    private $format;
    private $file;
    
    private function __construct(File $file, Format $format)
    {
        $this->format = $format;
        $this->file = $file;
    }
    
    public static function create(string $folder, Format $format, string $basename = "config"): self
    {
        $folder = empty($folder) ? $folder : "{$folder}/";
        
        return new self(File::fromPath("{$folder}{$basename}{$format}", "w"), $format);
    }
    
    public function file(): File
    {
        return $this->file;
    }
    
    public function format(): Format
    {
        return $this->format;
    }
    
    public function __toString(): string
    {
        return "{$this->file}";
    }
}
