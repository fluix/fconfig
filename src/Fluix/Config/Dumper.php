<?php

declare(strict_types = 1);

namespace Fluix\Config;

interface Dumper
{
    public function dump(File $file, array $config): void;
}
