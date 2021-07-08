<?php

declare(strict_types = 1);

namespace Fluix\Config\Reader;

use Fluix\Config\Exception\Exception;
use Fluix\Config\File;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

final class MyCnfReaderTest extends TestCase
{
    private MyCnfReader $reader;
    private \org\bovigo\vfs\vfsStreamDirectory $root;
    private \org\bovigo\vfs\vfsStreamFile $config;
    private \org\bovigo\vfs\vfsStreamFile $undefined;
    
    public function testRead(): void
    {
        $this->config->withContent(
            <<<CONTENT
[client]
host=localhost
database=schema
password=secret
user=username
CONTENT
        );
        
        $actual = $this->reader->read(File::fromPath($this->config->url()));
        
        self::assertEquals(
            [
                "values" => [
                    "APP_MYSQL_HOST"     => "localhost",
                    "APP_MYSQL_DATABASE" => "schema",
                    "APP_MYSQL_PASSWORD" => "secret",
                    "APP_MYSQL_USER"     => "username",
                ],
            ],
            $actual
        );
    }
    
    public function testInvalidSectionInConfig(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Section [client] not found in ");
        
        $this->config->withContent(
            <<<CONTENT
[mysql]
host=localhost
password=secret
user=username
CONTENT
        );
    
        $this->reader->read(File::fromPath($this->config->url()));
    }
    
    public function testRequiredOptions(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Missed required options: database");
        
        $this->config->withContent(
            <<<CONTENT
[client]
host=localhost
password=secret
user=username
CONTENT
        );
    
        $this->reader->read(File::fromPath($this->config->url()));
    }
    
    public function testSupports(): void
    {
        foreach ([[$this->undefined->url(), false], [$this->config->url(), true]] as [$source, $expected]) {
            self::assertEquals($expected, $this->reader->supports(File::fromPath($source)));
        }
    }
    
    protected function setUp(): void
    {
        $this->reader    = new MyCnfReader();
        $this->root      = vfsStream::setup();
        $this->config    = vfsStream::newFile(".my.cnf")->at($this->root);
        $this->undefined = vfsStream::newFile("undefined.ini")->at($this->root);
    }
}
