<?php

declare(strict_types = 1);

namespace Fluix\Config\Parser;

use Fluix\Config\Exception\Exception;
use Fluix\Config\Factory;
use Fluix\Config\Test\CaseProvider;
use PHPUnit\Framework\TestCase;

class DefaultParserTest extends TestCase
{
    private $parser;
    private $crypter;
    
    public function testRead()
    {
        $case1 = CaseProvider::json("json");
        $case2 = CaseProvider::db("db");
        
        $case1->populate($this->crypter);
        
        $actual = $this->parser->parse($case1->source(), $case2->source());
        $expected = [
            "json" => $case1->expected(),
            "db" => $case2->expected(),
        ];
        
        self::assertEquals($expected, $actual);
    }
    
    public function testOverwrite()
    {
        $case1 = CaseProvider::json("json");
        $case2 = CaseProvider::db("json");
        
        $case2->populate($this->crypter);
    
        $actual = $this->parser->parse($case1->source(), $case2->source());
        $expected = ["json" => array_merge($case1->expected(), $case2->expected())];
    
        self::assertEquals($expected, $actual);
    }
    
    public function testRootKey()
    {
        $case1 = CaseProvider::json();
        $case2 = CaseProvider::db();
    
        $actual = $this->parser->parse($case1->source(), $case2->source());
        $expected = array_merge($case1->expected(), $case2->expected());
    
        self::assertEquals($expected, $actual);
    }
    
    public function testUnsupportedConfigs()
    {
        $case = CaseProvider::invalid();
        
        $this->expectException(Exception::class);
        $this->expectExceptionMessageMatches("/^Unable to read .* available readers\: /");
        
        $this->parser->parse($case->source());
    }
    
    protected function setUp(): void
    {
        $this->parser = Factory::parser(str_pad("", 16, "a"));
    }
}
