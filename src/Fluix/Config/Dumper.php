<?php

declare(strict_types = 1);

namespace Fluix\Config;

use Fluix\Config\Dump\Format;

interface Dumper
{
    public function dump(File $file, array $config): void;
    
    public function supports(Format $format): bool;
}
