<?php

declare(strict_types = 1);

namespace Fluix\Config\Parser;

use Fluix\Config\Exception\Exception;
use Fluix\Config\Parser;
use Fluix\Config\Reader;
use Fluix\Config\Source;
use Fluix\Config\ValueProcessor\LoyalValueProcessor;

final class DefaultParser implements Parser
{
    private $processor;
    private $readers;
    
    public function __construct(LoyalValueProcessor $processor, Reader ...$readers)
    {
        $this->processor = $processor;
        $this->readers = $readers;
    }
    
    public function parse(Source ...$configs): array
    {
        $parsed = [];
        foreach ($configs as $config) {
            $result = $this->read($config);
            if ("" === $config->section()) {
                $parsed = \array_replace_recursive($parsed, $result);
            } else {
                $parsed[$config->section()] = $parsed[$config->section()] ?? [];
                $parsed[$config->section()] = \array_replace_recursive($parsed[$config->section()], $result);
            }
        }
        return $parsed;
    }
    
    private function read(Source $config): array
    {
        $result = null;
        foreach ($this->readers as $reader) {
            if (!$reader->supports($config->source())) {
                continue;
            }
        
            return $this->process($reader->read($config->source()));
        }
        
        throw new Exception(
            sprintf(
                "Unable to read {$config->source()}, available readers: %s",
                implode(", ", array_map(function (Reader $reader) {
                    return get_class($reader);
                }, $this->readers))
            )
        );
    }
    
    private function process(array $config): array
    {
        foreach ($config as $key => $value) {
            $oldKey = $key;
            $key = $this->processor->process($key);
            if ($oldKey !== $key) {
                unset($config[$oldKey]);
            }
            
            if (\is_array($value)) {
                $config[$key] = $this->process($value);
                continue;
            }
            
            if (\is_string($value)) {
                $config[$key] = $this->processor->process($value);
            } else {
                $config[$key] = $value;
            }
        }
        
        return $config;
    }
}
