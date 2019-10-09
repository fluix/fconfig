<?php

declare(strict_types = 1);

namespace Fluix\Config\Dump;

use Fluix\Config\File;

final class Destination
{
    private $file;
    private $format;
    
    public function __construct(File $file, Format $format)
    {
        $this->file = $file;
        $this->format = $format;
    }
    
    public function file(): File
    {
        return $this->file;
    }
    
    public function format(): Format
    {
        return $this->format;
    }
}
