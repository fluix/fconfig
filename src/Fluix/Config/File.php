<?php

declare(strict_types = 1);

namespace Fluix\Config;

use Fluix\Config\Exception\InvalidArgumentException;

final class File
{
    const READ_LENGTH = 2048;
    
    private $file;
    
    private function __construct(\SplFileObject $file)
    {
        if (!$file->isReadable()) {
            throw new InvalidArgumentException("File {$file->getPathname()} must be readable");
        }
        $this->file = $file;
    }
    
    public static function fromPath(string $path, string $mode = "r")
    {
        return new self(new \SplFileObject($path, $mode));
    }
    
    public function read(): string
    {
        $content = "";
        $this->file->rewind();
        while (!$this->file->eof()) {
            $content .= (string)$this->file->fread(self::READ_LENGTH);
        }
        
        return $content;
    }
    
    public function write(string $content): void
    {
        $this->file->fwrite($content);
    }
    
    public function extension(): string
    {
        return $this->file->getExtension();
    }
    
    public function basename(): string
    {
        return $this->file->getBasename();
    }
    
    public function folder(): string
    {
        return dirname($this->file->getPathname());
    }
    
    public function __toString(): string
    {
        return $this->file->getPathname();
    }
}
