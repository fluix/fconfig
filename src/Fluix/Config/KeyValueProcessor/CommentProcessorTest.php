<?php

declare(strict_types = 1);

namespace Fluix\Config\KeyValueProcessor;

use PHPUnit\Framework\TestCase;

class CommentProcessorTest extends TestCase
{
    private $processor;
    
    public function dataProvider()
    {
        return [
            ["qwe", "qwe"],
            ["//comment", ""],
            ["// comment", ""],
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
        $this->processor = new CommentProcessor();
    }
}
