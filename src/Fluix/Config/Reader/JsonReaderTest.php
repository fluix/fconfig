<?php

declare(strict_types = 1);

namespace Fluix\Config\Reader;

use Fluix\Config\Exception\Exception;
use Fluix\Config\File;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

final class JsonReaderTest extends TestCase
{
    private JsonReader $reader;
    private \org\bovigo\vfs\vfsStreamDirectory $root;
    private \org\bovigo\vfs\vfsStreamFile $config;
    private \org\bovigo\vfs\vfsStreamFile $undefined;
    
    protected function setUp(): void
    {
        $this->reader    = new JsonReader();
        $this->root      = vfsStream::setup();
        $this->config    = vfsStream::newFile("config.json")->at($this->root);
        $this->undefined = vfsStream::newFile("undefined.txt")->at($this->root);
    }
    
    public function testSupports(): void
    {
        foreach ([[$this->undefined->url(), false], [$this->config->url(), true]] as [$source, $expected]) {
            self::assertEquals($expected, $this->reader->supports(File::fromPath($source)));
        }
    }
    
    public function testRead(): void
    {
        $this->config->withContent(
            <<<CONTENT
{
   "option1": "value1",
   "option2": "value2"
}
CONTENT
        );
        
        $actual = $this->reader->read(File::fromPath($this->config->url()));
        
        self::assertEquals(
            [
                "option1" => "value1",
                "option2" => "value2",
            ],
            $actual
        );
    }
    
    public function testInvalidJson(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Syntax error");
        
        $this->config->withContent(
            <<<CONTENT
{
   "option1": "value1",
   "option2": "value2", // broken
}
CONTENT
        );
        
        $this->reader->read(File::fromPath($this->config->url()));
    }
}
