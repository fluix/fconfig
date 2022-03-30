<?php

declare(strict_types = 1);

namespace Fluix\Config\KeyValueProcessor;

use Fluix\Config\KeyProcessor;
use Fluix\Config\ValueProcessor;

final class EnvProcessor implements ValueProcessor, KeyProcessor
{
    public function process(string $value): string
    {
        return \preg_replace_callback(
            "/\\$\{(.+?)\}/",
            function ($matches) {
                $match = $matches[0];
                $name  = $matches[1] ?: "";
            
                if ($name && (false !== $value = \getenv($name))) {
                    return $value;
                }
            
                return $match;
            },
            $value
        );
    }
}
