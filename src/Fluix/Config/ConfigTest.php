<?php

declare(strict_types = 1);

namespace Fluix\Config;

use Fluix\Config\Dump\Format;
use Fluix\Config\Test\CaseProvider;
use Fluix\Config\Test\Cases\ValidCase;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{
    private $config;
    private $destinationContent;
    private $destination;
    
    public function dumpProvider()
    {
        return [
            [CaseProvider::json()],
            [CaseProvider::db()],
        ];
    }
    
    /**
     * @dataProvider dumpProvider
     */
    public function testDump(ValidCase $case)
    {
        $this->config->dump($this->destination, $case->source());
        $content = $this->destinationContent->getContent();
        self::assertEquals($case->json(), $content);
    }
    
    public function testPostProcessor()
    {
        $case = CaseProvider::json();
        
        $postProcessor = $this->prophesize(PostProcessor::class);
        $postProcessor->supports($case->expected())
            ->shouldBeCalledOnce()
            ->willReturn(true);
    
        $postProcessor->process($case->expected())
            ->shouldBeCalledOnce();
        
        $this->config->withPostProcessor($postProcessor->reveal());
        $this->config->dump($this->destination, $case->source());
    }
    
    protected function setUp(): void
    {
        $this->config = Factory::jsonConfig(str_pad("", 16, "a"));
        
        $root = vfsStream::setup();
        $this->destinationContent = vfsStream::newFile(Format::json()->destination("config"))->at($root);
        $this->destination = File::fromPath($this->destinationContent->url(), "w");
    }
}
