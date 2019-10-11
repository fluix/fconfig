<?php

declare(strict_types = 1);

namespace Fluix\Config\Dump;

use Fluix\Config\Dumper;
use Fluix\Config\File;

final class JsonDumper implements Dumper
{
    public function dump(File $file, array $values): void
    {
        $file->write(json_encode($values, JSON_PRETTY_PRINT));
    }
    
    public function supports(Format $format): bool
    {
        return $format->equals(Format::json());
    }
}
