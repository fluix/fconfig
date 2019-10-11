<?php

declare(strict_types = 1);

namespace Fluix\Config\Reader;

use Fluix\Config\Exception\Exception;
use Fluix\Config\File;
use Fluix\Config\Reader;

class MyCnfReader implements Reader
{
    private const REQUIRED_OPTIONS = [
        "user",
        "password",
        "database",
        "host",
    ];
    
    public function read(File $source): array
    {
        $parsed = \parse_ini_string($source->read(), true);
        if (!isset($parsed["client"])) {
            throw new Exception("Section [client] not found in {$source}");
        }
        
        $diff = \array_diff_key(\array_flip(self::REQUIRED_OPTIONS), $parsed["client"]);
        if (count($diff) > 0) {
            throw new Exception("Missed required options: " . \implode(", ", \array_keys($diff)));
        }
        
        return $this->toResponse($parsed["client"]);
    }
    
    public function supports(File $source): bool
    {
        return ".my.cnf" === $source->basename();
    }
    
    private function toResponse(array $client): array
    {
        $response = [];
        foreach ($client as $key => $value) {
            $response["APP_MYSQL_" . \strtoupper($key)] = $value;
        }
        
        return ["values" => $response];
    }
}
