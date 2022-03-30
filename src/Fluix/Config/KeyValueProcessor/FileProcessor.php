<?php

declare(strict_types=1);

namespace Fluix\Config\KeyValueProcessor;

use Fluix\Config\Exception\Exception;
use Fluix\Config\File;
use Fluix\Config\KeyProcessor;
use Fluix\Config\Reader;
use Fluix\Config\ValueProcessor;

final class FileProcessor implements ValueProcessor, KeyProcessor
{
    private array $data = [];

    public function __construct(File $file, Reader ...$readers)
    {
        foreach ($readers as $reader) {
            if ($reader->supports($file)) {
                $this->data = $reader->read($file);
                return;
            }
        }
        throw Exception::unreadableFile($file, ...$readers);
    }

    public function process(string $value): string
    {
        return \preg_replace_callback(
            "/\\$\{(.+?)\}/",
            function ($matches) {
                $match = $matches[0];
                $name  = $matches[1] ?? "";

                return $this->data[$name] ?? $match;
            },
            $value
        );
    }
}
