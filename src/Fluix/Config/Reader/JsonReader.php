<?php

declare(strict_types = 1);

namespace Fluix\Config\Reader;

use Fluix\Config\Exception\Exception;
use Fluix\Config\File;
use Fluix\Config\Reader;

final class JsonReader implements Reader
{
    public function read(File $source): array
    {
        try {
            return \json_decode($source->read(), true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            throw new Exception($e->getMessage(), $e->getCode(), $e);
        }
    }
    
    public function supports(File $source): bool
    {
        return "json" === $source->extension();
    }
}
