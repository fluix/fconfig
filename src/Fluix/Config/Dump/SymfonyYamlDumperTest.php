<?php

declare(strict_types = 1);

namespace Fluix\Config\Dump;

use Fluix\Config\Dumper;

class SymfonyYamlDumperTest extends AbstractDumperTest
{
    public function dumpProvider(): array
    {
        $expected = <<<JSON
parameters:
    qwe: 1
JSON;
    
        return [
            [["qwe" => 1], $expected],
        ];
    }
    
    protected function format(): Format
    {
        return Format::yaml();
    }
    
    protected function dumper(): Dumper
    {
        return new SymfonyYamlDumper();
    }
}
