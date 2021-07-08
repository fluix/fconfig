<?php

declare(strict_types = 1);

namespace Fluix\Config\Dump;

use Fluix\Config\Dumper;

final class PhpConstDumperTest extends AbstractDumperTest
{
    public function dumpProvider(): array
    {
        $expected = <<<CONTENT
<?php
// generated by Fluix\Config\Dump\PhpConstDumper, do not edit
define('qwe', 1);
CONTENT;
        
        return [
            [["qwe" => 1], $expected],
        ];
    }
    
    protected function format(): Format
    {
        return Format::const();
    }
    
    protected function dumper(): Dumper
    {
        return new PhpConstDumper();
    }
}
