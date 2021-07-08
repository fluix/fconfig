<?php

declare(strict_types = 1);

namespace Fluix\Config;

final class ParserResult
{
    private array $values;
    private array $extra;
    private array $required;
    
    private function __construct(array $values, array $required, array $extra)
    {
        $this->values   = $values;
        $this->required = $required;
        $this->extra    = $extra;
    }
    
    public static function fromConfig(array $config): self
    {
        $values   = isset($config["values"]) ? (array)$config["values"] : [];
        $required = isset($config["required"]) ? (array)$config["required"] : [];
        unset($config["values"], $config["required"]);
        
        return new self($values, \array_values($required), $config);
    }
    
    public function values(): array
    {
        return $this->values;
    }
    
    public function required(): array
    {
        return $this->required;
    }
    
    public function extra(): array
    {
        return $this->extra;
    }
    
    public function toArray(): array
    {
        $values   = empty($this->values) ? [] : ["values" => $this->values];
        $required = empty($this->required) ? [] : ["required" => $this->required];
        
        return $values + $required + $this->extra;
    }
}
