<?php

declare(strict_types = 1);

namespace Fluix\Config;

use Fluix\Config\Crypt\DefaultCrypt;
use Fluix\Config\Crypt\Secret;
use Fluix\Config\Dump\JsonDumper;
use Fluix\Config\Dump\PhpConstDumper;
use Fluix\Config\Dump\PhpDumper;
use Fluix\Config\Parser\DefaultParser;
use Fluix\Config\Reader\JsonReader;
use Fluix\Config\Reader\MyCnfReader;
use Fluix\Config\ValueProcessor\DecryptVariableProcessor;
use Fluix\Config\ValueProcessor\EnvVariableProcessor;
use Fluix\Config\ValueProcessor\LoyalValueProcessor;

final class Factory
{
    public static function jsonConfig(string $secret): Config
    {
        return new Config(
            self::parser($secret),
            new JsonDumper
        );
    }
    
    public static function constConfig(string $secret): Config
    {
        return new Config(
            self::parser($secret),
            new PhpConstDumper
        );
    }
    
    public static function arrayConfig(string $secret): Config
    {
        return new Config(
            self::parser($secret),
            new PhpDumper
        );
    }
    
    public static function parser(string $secret): Parser
    {
        return new DefaultParser(
            new LoyalValueProcessor(
                new EnvVariableProcessor,
                new DecryptVariableProcessor(
                    new DefaultCrypt(Secret::fromString($secret))
                )
            ),
            new MyCnfReader,
            new JsonReader
        );
    }
}
