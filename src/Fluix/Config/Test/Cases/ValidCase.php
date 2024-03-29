<?php

namespace Fluix\Config\Test\Cases;

use Fluix\Config\Template;

final class ValidCase extends BaseCase
{
    private array $expected;
    private string $json;
    
    public function __construct(Template $source, array $env, array $expected, string $json)
    {
        parent::__construct($source, $env);
        $this->expected = $expected;
        $this->json     = $json;
    }
    
    public function expected(): array
    {
        return $this->expected;
    }
    
    public function json(): string
    {
        return $this->json;
    }
}
