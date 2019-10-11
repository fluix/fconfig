<?php

declare(strict_types = 1);

namespace Fluix\Config;

use Fluix\Config\Dump\Format;

interface Dumper
{
    public function dump(File $file, array $values): void;
    
    public function supports(Format $format): bool;
}
