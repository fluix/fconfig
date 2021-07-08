<?php

declare(strict_types = 1);

namespace Fluix\Config\Dump;

use Fluix\Config\Dumper;

final class JsonDumperTest extends AbstractDumperTest
{
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
    
    protected function format(): Format
    {
        return Format::json();
    }
    
    protected function dumper(): Dumper
    {
        return new JsonDumper();
    }
}
