<?php

declare(strict_types = 1);

namespace Fluix\Config;

final class Config
{
    private $parser;
    /** @var PostProcessor[] */
    private $processors = [];
    private $dumper;
    
    public function __construct(Parser $parser, Dumper $dumper)
    {
        $this->parser = $parser;
        $this->dumper = $dumper;
    }
    
    public function withPostProcessor(PostProcessor ...$processors): self
    {
        $this->processors = $processors;
        
        return $this;
    }
    
    public function dump(File $destination, Source ...$configs): void
    {
        $result = $this->parser->parse(...$configs);
        $this->dumper->dump($destination, $result);
        
        $this->postProcess($result);
    }
    
    private function postProcess(array $result): void
    {
        foreach ($this->processors as $processor) {
            if (!$processor->supports($result)) {
                continue;
            }
        
            $processor->process($result);
        }
    }
}
