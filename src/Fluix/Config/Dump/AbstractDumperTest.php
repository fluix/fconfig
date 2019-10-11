<?php

declare(strict_types = 1);

namespace Fluix\Config\Dump;

use Fluix\Config\Dumper;
use Fluix\Config\File;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

abstract class AbstractDumperTest extends TestCase
{
    private $dumper;
    private $content;
    private $file;
    
    abstract public function dumpProvider(): array;
    
    public function formatProvider(): array
    {
        return array_map(
            function (Format $format) {
                return [$format, $format->equals($this->format())];
            },
            Format::all()
        );
    }
    
    /**
     * @dataProvider formatProvider
     */
    final public function testSupports(Format $format, bool $expected): void
    {
        self::assertEquals(
            $expected,
            $this->dumper->supports($format),
            sprintf(
                "Expect {$format} for %s to be %s",
                get_class($this->dumper),
                var_export($expected, true)
            )
        );
    }
    
    /**
     * @dataProvider dumpProvider
     */
    final public function testDump(array $config, $expected): void
    {
        $this->dumper->dump($this->file, $config);
        self::assertEquals($expected, preg_replace("/(.*)([\s]+)$/m", "$1", $this->content->getContent()));
    }
    
    protected function setUp(): void
    {
        $this->dumper = $this->dumper();
        $this->content = vfsStream::newFile($this->format()->destination("config"))->at(vfsStream::setup());
        $this->file = File::fromPath($this->content->url(), "w");
    }
    
    abstract protected function format(): Format;
    abstract protected function dumper(): Dumper;
}
