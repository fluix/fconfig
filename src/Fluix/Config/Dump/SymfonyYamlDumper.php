<?php

declare(strict_types = 1);

namespace Fluix\Config\Dump;

use Fluix\Config\Dumper;
use Fluix\Config\File;
use Symfony\Component\Yaml\Yaml;

final class SymfonyYamlDumper implements Dumper
{
    public function dump(File $file, array $values): void
    {
        $string = Yaml::dump(["parameters" => $values], 2, 4, Yaml::DUMP_EXCEPTION_ON_INVALID_TYPE);
        $file->write($string);
    }
    
    public function supports(Format $format): bool
    {
        return $format->equals(Format::yaml());
    }
}
