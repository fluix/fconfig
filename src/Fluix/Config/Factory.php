<?php

declare(strict_types = 1);

namespace Fluix\Config;

use Fluix\Config\Crypt\DefaultCrypt;
use Fluix\Config\Crypt\Secret;
use Fluix\Config\Dump\JsonDumper;
use Fluix\Config\Dump\PhpConstDumper;
use Fluix\Config\Dump\PhpDumper;
use Fluix\Config\Dump\SymfonyYamlDumper;
use Fluix\Config\KeyValueProcessor\CommentProcessor;
use Fluix\Config\KeyValueProcessor\DecryptProcessor;
use Fluix\Config\KeyValueProcessor\EnvProcessor;
use Fluix\Config\KeyValueProcessor\LoyalKeyProcessor;
use Fluix\Config\KeyValueProcessor\LoyalValueProcessor;
use Fluix\Config\Reader\JsonReader;
use Fluix\Config\Reader\MyCnfReader;
use Fluix\Config\Reader\RecursiveReader;

final class Factory
{
    public static function config(string $secret, callable ...$processors): Config
    {
        $config = new Config(
            self::parser($secret),
            new JsonDumper,
            new PhpConstDumper,
            new PhpDumper,
            new SymfonyYamlDumper
        );
        
        $config->withPostProcessors(...$processors);
        
        return $config;
    }
    
    public static function parser(string $secret): Parser
    {
        return new Parser(
            new LoyalKeyProcessor(
                new EnvProcessor,
                new DecryptProcessor(
                    new DefaultCrypt(Secret::fromString($secret))
                ),
                new CommentProcessor
            ),
            new LoyalValueProcessor(
                new EnvProcessor,
                new DecryptProcessor(
                    new DefaultCrypt(Secret::fromString($secret))
                )
            ),
            new MyCnfReader,
            new RecursiveReader(new JsonReader)
        );
    }
}
