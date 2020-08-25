<?php

declare(strict_types = 1);

namespace Fluix\Config\PreProcessor;

use Fluix\Config\Test\EnvPopulation;
use PHPUnit\Framework\TestCase;

final class AutoEnvExpanderTest extends TestCase
{
    private $expander;
    private $prefix = "AUTOENV_";
    
    public function cases(): array
    {
        
        return [
            [
                [
                    "{$this->prefix}TEST1" => "test1",
                    "{$this->prefix}test2" => "test2",
                    "no-prefix-test3"      => "test3",
                ],
                [
                    "TEST1" => "test1",
                    "test2" => "test2",
                ],
            ],
        ];
    }
    
    /**
     * @dataProvider cases
     */
    public function testCases(array $env, array $expected)
    {
        $env = new EnvPopulation($env);
        $env->populate(null);
        $actual = $this->expander->expand();
        $env->depopulate();
        
        $this->assertCount(\count($expected), $actual);
        
        foreach ($expected as $expectedKey => $expectedValue) {
            $this->assertArrayHasKey($expectedKey, $actual);
            $this->assertEquals($actual[$expectedKey], $expectedValue);
        }
    }
    
    protected function setUp(): void
    {
        $this->expander = new AutoEnvExpander($this->prefix);
    }
}
