<?php

declare(strict_types = 1);

namespace Fluix\Config\Reader;

use Fluix\Config\File;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

final class RecursiveReaderTest extends TestCase
{
    private RecursiveReader $reader;
    
    protected function setUp(): void
    {
        $this->reader = new RecursiveReader(new JsonReader);
    }
    
    public function testBaseConfig(): void
    {
        $root = vfsStream::setup();
        vfsStream::newFile("config-default.json")
            ->at($root)
            ->withContent(
                <<<JSON
{
    "port": 3306,
    "host": "localhost"
}
JSON
            );
    
        $config = vfsStream::newFile("config.json")
            ->at($root)
            ->withContent(
                <<<JSON
{
    "base": "config-default.json",
    "database": "schema",
    "port": 3336
}
JSON
            );
        
        $result = $this->reader->read(File::fromPath($config->url()));
        self::assertEquals(["port" => 3336, "database" => "schema", "host" => "localhost"], $result);
    }
    
    public function testBaseConfigDoesntExist(): void
    {
        $config = vfsStream::newFile("config.json")
            ->at(vfsStream::setup())
            ->withContent(
                <<<JSON
{
    "base": "config-default.json",
    "database": "schema",
    "port": 3336
}
JSON
            );
    
        $result = $this->reader->read(File::fromPath($config->url()));
        self::assertEquals(["port" => 3336, "database" => "schema"], $result);
    }
}
