<?php

declare(strict_types = 1);

namespace Fluix\Config;

use PHPUnit\Framework\TestCase;

final class ParserResultTest extends TestCase
{
    public function provider(): array
    {
        return [
                [
                    [], // empty config
                    [], // empty values
                ],
                [
                    [
                        "custom" => ["customOption1" => "customOption2"],
                    ],
                    [],
                ],
                [
                    [
                        "values" => [
                            "option1" => "value1",
                            "option2" => 2,
                            "option3" => true,
                        ],
                        "custom" => ["customOption1" => "customOption2"],
                    ],
                    [
                        "option1" => "value1",
                        "option2" => 2,
                        "option3" => true,
                    ],
                ],
        ];
    }
    
    /**
     * @dataProvider provider
     */
    public function testInstantiation(array $config, array $values): void
    {
        $result = ParserResult::fromConfig($config);
        self::assertEquals($values, $result->values());
        self::assertEquals($config, $result->toArray());
    }
}
