<?php

declare(strict_types = 1);

namespace Fluix\Config\ValueProcessor;

use PHPUnit\Framework\TestCase;

class EnvVariableProcessorTest extends TestCase
{
    private $processor;
    
    public function dataProvider()
    {
        return [
            ["qwe", "qwe"],
            ["\${QWE}", "\${QWE}"],
            ["\${QWE}", "abc", "QWE=abc"],
        ];
    }
    
    /**
     * @dataProvider dataProvider
     */
    public function testSubstitute(string $value, string $expected, string $env = "")
    {
        if (!empty($env)) {
            putenv($env);
        }
        
        $actual = $this->processor->process($value);
        self::assertEquals($expected, $actual);
    }
    
    protected function setUp(): void
    {
        $this->processor = new EnvVariableProcessor();
    }
}
