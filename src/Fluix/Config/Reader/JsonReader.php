<?php

declare(strict_types = 1);

namespace Fluix\Config\Reader;

use Fluix\Config\Exception\Exception;
use Fluix\Config\File;
use Fluix\Config\Reader;

// phpcs:disable PHPCompatibility.Classes.NewClasses.jsonexceptionFound
class JsonReader implements Reader
{
    public function read(File $source): array
    {
        try {
            return \Fluix\Config\json_decode($source->read(), true);
        } catch (\JsonException $e) {
            throw new Exception($e->getMessage(), $e->getCode(), $e);
        }
    }
    
    public function supports(File $source): bool
    {
        return "json" === $source->extension();
    }
}
