<?php

declare(strict_types = 1);

namespace Fluix\Config;

interface PostProcessor
{
    public function process(array $config): void;
    
    public function supports(array $config): bool;
}
