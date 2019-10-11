<?php

declare(strict_types = 1);

namespace Fluix\Config\Parser;

use Fluix\Config\Crypt\DefaultCrypt;
use Fluix\Config\Crypt\Secret;
use Fluix\Config\Exception\Exception;
use Fluix\Config\Factory;
use Fluix\Config\Source;
use Fluix\Config\Test\CaseProvider;
use PHPUnit\Framework\TestCase;

class ParserTest extends TestCase
{
    private $parser;
    private $crypt;
    
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->crypt = new DefaultCrypt(Secret::fromString(str_pad("", 16, "a")));
    }
    
    public function provider()
    {
        $case1 = CaseProvider::json();
        $case2 = CaseProvider::db();
        $case1->populate($this->crypt);
        
        return [
            [$case1->source(), $case1->expected()],
            [$case2->source(), $case2->expected()],
        ];
    }
    
    /** @dataProvider provider */
    public function testCases(Source $source, array $expected)
    {
        $actual = $this->parser->parse($source);
        self::assertEquals($expected, $actual->toArray());
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
