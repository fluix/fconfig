<?php

namespace Fluix\Config\Test\Cases;

use Fluix\Config\Crypt;
use Fluix\Config\Source;
use Fluix\Config\Test\EnvPopulation;

class BaseCase
{
    private $source;
    private $env;
    
    public function __construct(Source $source, array $env)
    {
        $this->source = $source;
        $this->env = new EnvPopulation($env);
        $this->populate(null);
    }
    
    public function source(): Source
    {
        return $this->source;
    }
    
    public function populate(?Crypt $crypt): void
    {
        $this->env->populate($crypt);
    }
    
    public function depopulate(): void
    {
        $this->env->depopulate();
    }
    
    public function __toString()
    {
        return (string)$this->source->source();
    }
}
