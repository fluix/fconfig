<?php

declare(strict_types = 1);

namespace Fluix\Config\KeyValueProcessor;

use Fluix\Config\ValueProcessor;

final class LoyalValueProcessor
{
    /** @var ValueProcessor[] */
    private array $processors;
    
    public function __construct(ValueProcessor ...$processors)
    {
        $this->processors = $processors;
    }
    
    /**
     * @param mixed $value
     * @return mixed
     */
    public function process($value)
    {
        if (!\is_string($value)) {
            return $value;
        }
    
        foreach ($this->processors as $processor) {
            $value = $processor->process($value);
        }
        
        return $value;
    }
}
