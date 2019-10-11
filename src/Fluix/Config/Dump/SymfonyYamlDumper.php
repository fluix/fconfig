<?php

declare(strict_types = 1);

namespace Fluix\Config\Dump;

use Fluix\Config\Dumper;
use Fluix\Config\File;

final class SymfonyYamlDumper implements Dumper
{
    public function dump(File $file, array $values): void
    {
        ob_start();
        echo "parameters:\n";
        foreach ($values as $k => $v) {
            $val = "'{$v}'";
            if (is_bool($v)) {
                $val = $v ? 'true' : 'false';
            } elseif (is_numeric($v)) {
                $val = $v;
            }
            echo "    {$k}: {$val}\n";
        }
        
        $file->write(ob_get_clean());
    }
    
    public function supports(Format $format): bool
    {
        return $format->equals(Format::yaml());
    }
}
