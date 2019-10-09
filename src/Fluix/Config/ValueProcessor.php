<?php

declare(strict_types = 1);

namespace Fluix\Config;

interface ValueProcessor
{
    public function process(string $value): string;
}
