<?php

declare(strict_types = 1);

namespace Fluix\Config\PreProcessor;

use Fluix\Config\PreProcessorInterface;

final class AutoEnvPreProcessor implements PreProcessorInterface
{
    private $expander;
    
    public function __construct(string $prefix = "AUTOENV_")
    {
        $this->expander = new AutoEnvExpander($prefix);
    }
    
    public function preprocess(array $config): array
    {
        $expanded = $this->expander->expand();
        if (0 === \count($expanded)) {
            return $config;
        }
        
        $config["values"] = $config["values"] ?? [];
        foreach ($expanded as $key => $value) {
            $config["values"][$key] = $config["values"][$key] ?? $value;
        }
        
        return $config;
    }
}
