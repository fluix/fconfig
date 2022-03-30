<?php

namespace Fluix\Config\Test\Cases;

use Fluix\Config\Crypt;
use Fluix\Config\Template;

class BaseCase
{
    private Template $source;
    private array $env;
    
    public function __construct(Template $source, array $env)
    {
        $this->source = $source;
        $this->env    = $env;
        $this->populate(null);
    }
    
    public function source(): Template
    {
        return $this->source;
    }
    
    public function populate(?Crypt $crypt): void
    {
        foreach ($this->env as $key => $value) {
            $value = (null !== $crypt) ? $crypt->encrypt($value) : $value;
            \putenv(\implode("=", [$key, $value]));
        }
    }
    
    public function __toString()
    {
        return (string)$this->source->template();
    }
}
