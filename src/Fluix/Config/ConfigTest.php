<?php

declare(strict_types = 1);

namespace Fluix\Config;

use Fluix\Config\Dump\Destination;
use Fluix\Config\Dump\Format;
use Fluix\Config\Exception\Exception;
use Fluix\Config\Test\CaseProvider;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

final class ConfigTest extends TestCase
{
    private Config $config;
    private Format $format;
    private Destination $destination;
    private \org\bovigo\vfs\vfsStreamFile $content;
    
    public function testValidContent(): void
    {
        $case = CaseProvider::db();
        $this->config->dump($case->source(), $this->destination);
        $content = $this->content->getContent();
        self::assertEquals($case->json(), $content);
    }
    
    public function testMissedRequired(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessageMatches("/^Missing required option/");
        
        $case = CaseProvider::missedRequired();
        $this->config->dump($case->source(), $this->destination);
    }
    
    protected function setUp(): void
    {
        $this->format = Format::json();
        $this->config = Factory::config(\str_pad("", 16, "a"), null);
        
        $this->content     = vfsStream::newFile($this->format->destination("config"))->at(vfsStream::setup());
        $this->destination = Destination::create(\dirname($this->content->url()), $this->format, "config");
    }
}
