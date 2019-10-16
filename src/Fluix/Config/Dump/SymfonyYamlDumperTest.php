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
    option1: value1
    database: schema
    boolean: false
    'null': null
    secret_value: secret-value-here
    int: 397
    object: { key: 21 }
    array: [{ option31: test_env }]
    nested: { child1: { child2: { env: test_env_value_json2 } } }
JSON;
    
        return [
            [
                [
                    "option1"      => "value1",
                    "database"     => "schema",
                    "boolean"      => false,
                    "null"         => null,
                    "secret_value" => "secret-value-here",
                    "int"          => 397,
                    "object"       => [
                        "key" => 21,
                    ],
                    "array"        => [
                        [
                            "option31" => "test_env",
                        ],
                    ],
                    "nested"       => [
                        "child1" => [
                            "child2" => [
                                "env" => "test_env_value_json2",
                            ],
                        ],
                    ],
                ],
                $expected,
            ],
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
