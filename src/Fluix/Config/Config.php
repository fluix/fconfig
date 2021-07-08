<?php

declare(strict_types = 1);

namespace Fluix\Config;

use Fluix\Config\Dump\Destination;
use Fluix\Config\Dump\Format;
use Fluix\Config\Exception\Exception;
use Fluix\Config\Exception\InvalidArgumentException;

final class Config
{
    private Parser $parser;
    /** @var Dumper[] */
    private array $dumpers;
    /** @var callable[] */
    private $processors = [];
    
    public function __construct(Parser $parser, Dumper ...$dumpers)
    {
        $this->parser  = $parser;
        $this->dumpers = $dumpers;
    }
    
    public function withPostProcessors(callable ...$processors): self
    {
        $this->processors = $processors;
        return $this;
    }
    
    public function dump(Source $config, Destination ...$destinations): void
    {
        $result = $this->parser->parse($config);
        $this->assertRequired($result);
        
        foreach ($destinations as $destination) {
            $this->dumperFor($destination->format())->dump($destination->file(), $result->values());
        }
        
        foreach ($this->processors as $processor) {
            \call_user_func($processor, $result);
        }
    }
    
    public function assertRequired(ParserResult $result): void
    {
        foreach ($result->required() as $required) {
            if (!\array_key_exists($required, $result->values())) {
                throw new Exception("Missing required option '{$required}'");
            }
        }
    }
    
    private function dumperFor(Format $format): Dumper
    {
        foreach ($this->dumpers as $dumper) {
            if ($dumper->supports($format)) {
                return $dumper;
            }
        }
        
        throw new InvalidArgumentException("No dumpers available for format {$format}");
    }
}
