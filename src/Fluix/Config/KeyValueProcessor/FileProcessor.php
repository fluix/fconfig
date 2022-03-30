<?php

declare(strict_types=1);

namespace Fluix\Config\KeyValueProcessor;

use Fluix\Config\Exception\Exception;
use Fluix\Config\KeyProcessor;
use Fluix\Config\Reader;
use Fluix\Config\Source;
use Fluix\Config\ValueProcessor;

final class FileProcessor implements ValueProcessor, KeyProcessor
{
    private array $data = [];

    public function __construct(Source $source, Reader ...$readers)
    {
        foreach ($readers as $reader) {
            if ($reader->supports($source->source())) {
                $this->data = $reader->read($source->source());
                return;
            }
        }
        throw Exception::unreadableFile($source->source(), ...$readers);
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
