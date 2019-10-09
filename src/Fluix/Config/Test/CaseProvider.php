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
    public static function json(string $section = ""): ValidCase
    {
        $file = vfsStream::newFile("config.json")
            ->at(self::root())
            ->withContent(
                <<<JSON
{
    "option1": "value1",
    "database": "mm",
    "boolean": false,
    "null": null,
    "int": 397,
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
    }
}
JSON
            );
    
        return new ValidCase(
            Source::fromPath($file->url(), $section),
            ["TEST_ENV_JSON1" => "test_env_value_json1", "TEST_ENV_JSON2" => "test_env_value_json2"],
            [
                "option1"  => "value1",
                "database" => "mm",
                "boolean"  => false,
                "null"     => null,
                "int"      => 397,
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
            <<<JSON
{
    "option1": "value1",
    "database": "mm",
    "boolean": false,
    "null": null,
    "int": 397,
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
    }
}
JSON
        );
    }
    
    public static function db(string $section = ""): ValidCase
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
            Source::fromPath($file->url(), $section),
            ["TEST_ENV_DB" => "test_env_value_db"],
            [
                "host"     => "localhost",
                "database" => "schema",
                "password" => "secret",
                "user"     => "test_env_value_db",
            ],
            <<<JSON
{
    "host": "localhost",
    "database": "schema",
    "password": "secret",
    "user": "test_env_value_db"
}
JSON
        );
    }
    
    public static function invalid(string $section = ""): BaseCase
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
            Source::fromPath($file->url(), $section),
            []
        );
    }
    
    private static function root(): vfsStreamDirectory
    {
        return vfsStream::setup();
    }
}
