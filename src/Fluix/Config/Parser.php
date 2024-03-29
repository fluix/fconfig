<?php

declare(strict_types = 1);

namespace Fluix\Config;

use Fluix\Config\Exception\Exception;
use Fluix\Config\KeyValueProcessor\LoyalKeyProcessor;
use Fluix\Config\KeyValueProcessor\LoyalValueProcessor;

final class Parser
{
    private LoyalKeyProcessor $keyProcessor;
    private LoyalValueProcessor $valueProcessor;
    /** @var Reader[] */
    private array $readers;
    
    public function __construct(LoyalKeyProcessor $keyProcessor, LoyalValueProcessor $valueProcessor, Reader ...$readers)
    {
        $this->keyProcessor   = $keyProcessor;
        $this->valueProcessor = $valueProcessor;
        $this->readers        = $readers;
    }
    
    public function parse(Template $config): ParserResult
    {
        foreach ($this->readers as $reader) {
            if (!$reader->supports($config->template())) {
                continue;
            }
        
            return $this->process($reader->read($config->template()));
        }
        
        throw Exception::unreadableFile($config->template(), ...$this->readers);
    }
    
    private function process(array $config): ParserResult
    {
        foreach ($config as $key => $value) {
            $oldKey = $key;
            $key    = $this->keyProcessor->process($key);
            if ($oldKey !== $key) {
                unset($config[$oldKey]);
            }
            
            if (!\is_numeric($key) && empty((string)$key)) {
                continue;
            }
            
            if (\is_array($value)) {
                $config[$key] = $this->process($value)->toArray();
                continue;
            }
            
            if (\is_string($value)) {
                $config[$key] = $this->valueProcessor->process($value);
            } else {
                $config[$key] = $value;
            }
        }
        
        return ParserResult::fromConfig($config);
    }
}
