<?php

declare(strict_types = 1);

namespace Fluix\Config;

interface KeyProcessor
{
    public function process(string $key): string;
}
