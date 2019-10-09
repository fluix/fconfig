<?php

declare(strict_types = 1);

namespace Fluix\Config\Dump;

use Fluix\Config\Dumper;

class JsonDumperTest extends AbstractDumperTest
{
    public function supportProvider(): array
    {
        return [
            ["json", true],
            ["const", false],
            ["php", false],
        ];
    }
    
    public function dumpProvider(): array
    {
        $expected = <<<JSON
{
    "qwe": 1
}
JSON;
        
        return [
            [["qwe" => 1], $expected],
        ];
    }
    
    protected function basename(): string
    {
        return "config.json";
    }
    
    protected function dumper(): Dumper
    {
        return new JsonDumper();
    }
}
