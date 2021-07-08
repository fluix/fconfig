<?php

declare(strict_types = 1);

namespace Fluix\Config\KeyValueProcessor;

use PHPUnit\Framework\TestCase;

final class CommentProcessorTest extends TestCase
{
    private CommentProcessor $processor;
    
    public function dataProvider(): array
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
    public function testSubstitute(string $value, string $expected, string $env = ""): void
    {
        if (!empty($env)) {
            \putenv($env);
        }
        
        $actual = $this->processor->process($value);
        self::assertEquals($expected, $actual);
    }
    
    protected function setUp(): void
    {
        $this->processor = new CommentProcessor();
    }
}
