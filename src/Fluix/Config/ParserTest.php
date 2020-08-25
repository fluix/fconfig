<?php

declare(strict_types = 1);

namespace Fluix\Config\Parser;

use Fluix\Config\Crypt\DefaultCrypt;
use Fluix\Config\Crypt\Secret;
use Fluix\Config\Exception\Exception;
use Fluix\Config\Factory;
use Fluix\Config\Test\CaseProvider;
use Fluix\Config\Test\Cases\BaseCase;
use PHPUnit\Framework\TestCase;

class ParserTest extends TestCase
{
    private $parser;
    private $crypt;
    /** @var BaseCase[] */
    private $cases = [];
    
    public function testJson()
    {
        $case = $this->case(CaseProvider::json());
        $actual = $this->parser->parse($case->source());
        self::assertEquals($case->expected(), $actual->toArray());
    }
    
    public function testDb()
    {
        $case = $this->case(CaseProvider::db());
        $actual = $this->parser->parse($case->source());
        self::assertEquals($case->expected(), $actual->toArray());
    }
    
    public function testUnsupportedConfigs()
    {
        $case = CaseProvider::invalid();
        
        $this->expectException(Exception::class);
        $this->expectExceptionMessageMatches("/^Unable to read .* available readers\: /");
        
        $this->parser->parse($case->source());
    }
    
    public function testAutoExpantion()
    {
        $case = $this->case(CaseProvider::autoExpand());
        $actual = $this->parser->parse($case->source());
        self::assertEquals($case->expected(), $actual->toArray());
    }
    
    protected function setUp(): void
    {
        $this->crypt = new DefaultCrypt(Secret::fromString(str_pad("", 16, "a")));
        $this->parser = Factory::parser(str_pad("", 16, "a"));
    }
    
    protected function tearDown(): void
    {
        foreach ($this->cases as $case) {
            $case->depopulate();
        }
        $this->cases = [];
    }
    
    private function case(BaseCase $case): BaseCase
    {
        $case->populate($this->crypt);
        $this->cases[] = $case;
        
        return $case;
    }
}
