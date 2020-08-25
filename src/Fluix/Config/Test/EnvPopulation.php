<?php

declare(strict_types = 1);

namespace Fluix\Config\Test;

use Fluix\Config\Crypt;

final class EnvPopulation
{
    private $env;
    
    public function __construct(array $env)
    {
        $this->env = $env;
    }
    
    public function populate(?Crypt $crypt): void
    {
        foreach ($this->env as $key => $value) {
            $value = (null !== $crypt) ? $crypt->encrypt($value) : $value;
            putenv(implode("=", [$key, $value]));
            $_ENV[$key] = $value;
            $_SERVER[$key] = $value;
        }
    }
    
    public function depopulate(): void
    {
        foreach ($this->env as $key => $value) {
            putenv("{$key}=");
            unset($_ENV[$key], $_SERVER[$key]);
        }
    }
}
