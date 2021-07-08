<?php

declare(strict_types = 1);

namespace Fluix\Config\KeyValueProcessor;

use PHPUnit\Framework\TestCase;

final class EnvProcessorTest extends TestCase
{
    private EnvProcessor $processor;
    
    public function dataProvider(): array
    {
        return [
            ["qwe", "qwe"],
            ["\${QWE}", "\${QWE}"],
            ["\${QWE}", "abc", "QWE=abc"],
            ["\${QWE}", "", "QWE="],
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
        $this->processor = new EnvProcessor();
    }
}
