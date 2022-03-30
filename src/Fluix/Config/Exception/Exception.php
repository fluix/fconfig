<?php

declare(strict_types = 1);

namespace Fluix\Config\Exception;

use Fluix\Config\File;
use Fluix\Config\Reader;

final class Exception extends \Exception
{
    public static function unreadableFile(File $file, Reader ...$readers): self
    {

        return new Exception(
            sprintf(
                "Unable to read {$file}, available readers: %s",
                \implode(", ", \array_map(function (Reader $reader) {
                    return get_class($reader);
                }, $readers))
            )
        );
    }
}
