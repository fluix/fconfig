<?php

declare(strict_types=1);

namespace Fluix\Config\KeyValueProcessor;

use Fluix\Config\File;
use Fluix\Config\Reader\JsonReader;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

class FileProcessorTest extends TestCase
{
    public function dataProvider(): array
    {
        return [
            [["key1" => "field1"], "qwe", "qwe"],
            [["key1" => "field1"], "\${QWE}", "\${QWE}"],
            [["key1" => "field1", "QWE" => "field3"], "\${QWE}", "field3"],
            [["key1" => "field1", "QWE" => ""], "\${QWE}", ""],
        ];
    }
    /** @dataProvider dataProvider */
    public function testSuccess(array $data, string $value, string $expected): void
    {
        $envFile = vfsStream::newFile("env.json")
            ->at(vfsStream::setup())
            ->withContent(\json_encode($data));
        $source  = File::fromPath($envFile->url());

        $fileProcessor = new FileProcessor($source, new JsonReader);
        $actual        = $fileProcessor->process($value);

        self::assertEquals($expected, $actual);
    }
}
