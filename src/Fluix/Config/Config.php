<?php

declare(strict_types = 1);

namespace Fluix\Config;

use Fluix\Config\Dump\Destination;
use Fluix\Config\Exception\InvalidArgumentException;

final class Config
{
    private $parser;
    /** @var PostProcessor[] */
    private $processors = [];
    private $dumpers;
    
    public function __construct(Parser $parser, Dumper ...$dumpers)
    {
        $this->parser = $parser;
        if (0 === count($dumpers)) {
            throw new InvalidArgumentException("Provide at least one dumper");
        }
        $this->dumpers = $dumpers;
    }
    
    public function withPostProcessor(PostProcessor ...$processors)
    {
        $this->processors = $processors;
        
        return $this;
    }
    
    public function dump(Destination $dump, Source ...$configs)
    {
        $result = $this->parser->parse(...$configs);
        
        foreach ($this->processors as $processor) {
            if (!$processor->supports($result)) {
                continue;
            }
            
            $processor->process($result);
        }
        
        foreach ($this->dumpers as $dumper) {
            if (!$dumper->supports($dump->format())) {
                continue;
            }
            
            $dumper->dump($dump->file(), $result);
        }
    }
}
