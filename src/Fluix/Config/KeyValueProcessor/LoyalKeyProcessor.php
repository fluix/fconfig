<?php

declare(strict_types = 1);

namespace Fluix\Config\KeyValueProcessor;

use Fluix\Config\KeyProcessor;

final class LoyalKeyProcessor
{
    private $processors;
    
    public function __construct(KeyProcessor ...$processors)
    {
        $this->processors = $processors;
    }
    
    public function process($value)
    {
        if (!is_string($value)) {
            return $value;
        }
    
        foreach ($this->processors as $processor) {
            $value = $processor->process($value);
        }
        
        return $value;
    }
}
