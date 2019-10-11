<?php

declare(strict_types = 1);

namespace Fluix\Config\KeyValueProcessor;

use Fluix\Config\KeyProcessor;

final class CommentProcessor implements KeyProcessor
{
    public function process(string $value): string
    {
        return 0 === strpos((string)$value, "//") ? "" : $value;
    }
}
