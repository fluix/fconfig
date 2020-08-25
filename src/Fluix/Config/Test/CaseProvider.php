<?php

declare(strict_types = 1);

namespace Fluix\Config\Test;

use Fluix\Config\Source;
use Fluix\Config\Test\Cases\BaseCase;
use Fluix\Config\Test\Cases\ValidCase;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;

final class CaseProvider
{
    public static function json(): ValidCase
    {
        $file = vfsStream::newFile("config.json")
            ->at(self::root())
            ->withContent(
                <<<JSON
{
    "values": {
        "option1": "value1",
        "database": "mm",
        "boolean": false,
        "null": null,
        "int": 397,
        "// commented-key": "val",
        "commented-value": "// commented-value",
        "object": {
            "key": 21
        },
        "array": [
            {
                "option31": "\${TEST_ENV_JSON1}"
            }
        ],
        "nested": {
            "child1": {
                "child2": {
                    "env": "test_env_value_json2"
                }
            }
        }
    },
    "required": [
       "option1",
       "database"
    ]
}
JSON
            );
    
        return new ValidCase(
            Source::fromPath($file->url()),
            ["TEST_ENV_JSON1" => "test_env_value_json1", "TEST_ENV_JSON2" => "test_env_value_json2"],
            [
                "values" => [
                    "option1"  => "value1",
                    "database" => "mm",
                    "boolean"  => false,
                    "null"     => null,
                    "int"      => 397,
                    "commented-value" => "// commented-value",
                    "object"   => [
                        "key" => 21,
                    ],
                    "array"    => [
                        [
                            "option31" => "test_env_value_json1",
                        ],
                    ],
                    "nested"   => [
                        "child1" => [
                            "child2" => [
                                "env" => "test_env_value_json2",
                            ],
                        ],
                    ],
                ],
                "required" => [
                    "option1",
                    "database",
                ],
            ],
            <<<JSON
{
    "values": {
        "option1": "value1",
        "database": "mm",
        "boolean": false,
        "null": null,
        "int": 397,
        "commented-value": "// commented-value",
        "object": {
            "key": 21
        },
        "array": [
            {
                "option31": "test_env_value_json1"
            }
        ],
        "nested": {
            "child1": {
                "child2": {
                    "env": "test_env_value_json2"
                }
            }
        },
        "required": [
           "option1",
           "database"
        ]
    }
}
JSON
        );
    }
    
    public static function db(): ValidCase
    {
        $file = vfsStream::newFile(".my.cnf")
            ->at(self::root())
            ->withContent(
                <<<JSON
[client]
host=localhost
database=schema
password=secret
user=\${TEST_ENV_DB}
JSON
            );
    
        return new ValidCase(
            Source::fromPath($file->url()),
            ["TEST_ENV_DB" => "test_env_value_db"],
            [
                "values" => [
                    "APP_MYSQL_HOST"     => "localhost",
                    "APP_MYSQL_DATABASE" => "schema",
                    "APP_MYSQL_PASSWORD" => "secret",
                    "APP_MYSQL_USER"     => "test_env_value_db",
                ],
            ],
            <<<JSON
{
    "APP_MYSQL_HOST": "localhost",
    "APP_MYSQL_DATABASE": "schema",
    "APP_MYSQL_PASSWORD": "secret",
    "APP_MYSQL_USER": "test_env_value_db"
}
JSON
        );
    }
    
    public static function invalid(): BaseCase
    {
        $file = vfsStream::newFile("invalid.none")
            ->at(self::root())
            ->withContent(
                <<<CONTENT
line1
line2
CONTENT
            );
        
        return new BaseCase(
            Source::fromPath($file->url()),
            []
        );
    }
    
    public static function missedRequired(): BaseCase
    {
        $file = vfsStream::newFile("invalid.required.json")
            ->at(self::root())
            ->withContent(
                <<<CONTENT
{
    "values": {
        "option1": "value1",
        "database": "mm"
    },
    "required": [
        "option1",
        "option2"
    ]
}
CONTENT
            );
        
        return new BaseCase(
            Source::fromPath($file->url()),
            []
        );
    }
    
    public static function autoExpand(): ValidCase
    {
        $file = vfsStream::newFile("auto-expand.json")
            ->at(self::root())
            ->withContent(
                <<<CONTENT
{
    "values": {}
}
CONTENT
            );
        
        return new ValidCase(
            Source::fromPath($file->url()),
            [
                "AUTOENV_TEST1" => "test1",
                "AUTOENV_TEST2" => "test2",
            ],
            [
                "values" => [
                    "TEST1"   => "test1",
                    "TEST2"   => "test2",
                ],
            ],
            <<<JSON
{
    "values": {
        "TEST1": "test1",
        "TEST2": "test2"
    }
}
JSON
        );
    }
    
    private static function root(): vfsStreamDirectory
    {
        return vfsStream::setup();
    }
}
