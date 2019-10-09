<?php

declare(strict_types = 1);

namespace Fluix\Config\ValueProcessor;

use Fluix\Config\ValueProcessor;

final class EnvVariableProcessor implements ValueProcessor
{
    public function process(string $value): string
    {
        return \preg_replace_callback(
            '/\${(.+?)}/',
            function ($matches) {
                $match = $matches[0];
                $name = $matches[1] ?: '';
            
                if ($name && ($value = \getenv($name))) {
                    return $value;
                }
            
                return $match;
            },
            $value
        );
    }
}
