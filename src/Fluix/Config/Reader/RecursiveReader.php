<?php

declare(strict_types = 1);

namespace Fluix\Config\Reader;

use Fluix\Config\File;
use Fluix\Config\Reader;

final class RecursiveReader implements Reader
{
    private Reader $origin;
    
    public function __construct(Reader $origin)
    {
        $this->origin = $origin;
    }
    
    public function read(File $source): array
    {
        $result = $this->origin->read($source);
        $base   = $result["base"] ?? null;
        unset($result["base"]);
        
        if ($base) {
            try {
                $result = \array_replace_recursive($this->read(File::fromPath("{$source->folder()}/{$base}")), $result);
            } catch (\RuntimeException $e) {
                $base = null;
            }
        }
        
        return $result;
    }
    
    public function supports(File $source): bool
    {
        return $this->origin->supports($source);
    }
}
