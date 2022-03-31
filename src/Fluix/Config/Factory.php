<?php

declare(strict_types = 1);

namespace Fluix\Config;

use Fluix\Config\Crypt\DefaultCrypt;
use Fluix\Config\Dump\JsonDumper;
use Fluix\Config\Dump\PhpConstDumper;
use Fluix\Config\Dump\PhpDumper;
use Fluix\Config\Dump\SymfonyYamlDumper;
use Fluix\Config\KeyValueProcessor\CommentProcessor;
use Fluix\Config\KeyValueProcessor\DecryptProcessor;
use Fluix\Config\KeyValueProcessor\EnvProcessor;
use Fluix\Config\KeyValueProcessor\LoyalKeyProcessor;
use Fluix\Config\KeyValueProcessor\LoyalValueProcessor;
use Fluix\Config\KeyValueProcessor\FileProcessor;
use Fluix\Config\Reader\JsonReader;
use Fluix\Config\Reader\MyCnfReader;
use Fluix\Config\Reader\RecursiveReader;
use Readdle\Crypt\Crypto;
use Readdle\Crypt\Secret;

final class Factory
{
    public static function config(string $secret, ?File $fallback, callable ...$processors): Config
    {
        $config = new Config(
            self::parser($secret, $fallback),
            new JsonDumper,
            new PhpConstDumper,
            new PhpDumper,
            new SymfonyYamlDumper
        );
        
        $config->withPostProcessors(...$processors);
        
        return $config;
    }
    
    public static function parser(string $secret, ?File $fallback = null): Parser
    {
        return new Parser(
            new LoyalKeyProcessor(...self::keyProcessors($secret, $fallback)),
            new LoyalValueProcessor(...self::valueProcessors($secret, $fallback)),
            new MyCnfReader,
            new RecursiveReader(new JsonReader)
        );
    }

    /** @return \Generator<KeyProcessor> */
    private static function keyProcessors(string $secret, ?File $fallback): \Generator
    {
        yield new EnvProcessor;
        if (null !== $fallback) {
            yield new FileProcessor($fallback, new JsonReader);
        }
        yield new DecryptProcessor(new DefaultCrypt(new Crypto(Secret::fromString($secret))));
        yield new CommentProcessor;
    }
    
    /** @return \Generator<ValueProcessor> */
    private static function valueProcessors(string $secret, ?File $fallback): \Generator
    {
        yield new EnvProcessor;
        if (null !== $fallback) {
            yield new FileProcessor($fallback, new JsonReader);
        }
        yield new DecryptProcessor(new DefaultCrypt(new Crypto(Secret::fromString($secret))));
    }
}
