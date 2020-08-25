<?php

declare(strict_types = 1);

namespace Fluix\Config\PreProcessor;

final class AutoEnvExpander
{
    private $prefix;
    
    public function __construct(string $prefix = "AUTOENV_")
    {
        $this->prefix = $prefix;
    }
    
    public function expand(): array
    {
        $expanded = [];
        foreach ($_ENV as $key => $value) {
            if (0 === \strpos($key, $this->prefix)) {
                $key = \substr($key, \strlen($this->prefix));
                $expanded[$key] = $value;
            }
        }
        return $expanded;
    }
}
