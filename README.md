FConfig Management
==============

[![Build Status](https://travis-ci.org/fluix/fconfig.svg?branch=master)](https://travis-ci.org/fluix/fconfig)

A PHP library for config management

## Installation

### With Composer

```
$ composer require fluix/fconfig
```

```json
{
    "require": {
        "fluix/fconfig": "^1.0"
    }
}
```

## Config examples

### default.json
```json
{
    "values": {
        "option1": "value1",
        "database": "schema"    
    }
}
```

### config.json
- Manage your nested configurations with key `base`  
- Define a list of required options with key `required`
- Use `//` to comment out config options
- Environment variables could be used via `${ENV_VARIABLE1}`
- Secure options are supported, e.g. `secret_value`. (use `bin/fconfig encrypt|decrypt {secret} {value}`)

```json
{
    "base": "default.json",
    "values": {
        "database": "schema-overridden",
        "boolean": false,
        "null": null,
        "secret_value": "-CRYPT-b7139469f60891ae1670b4a2c61777faa1ee84e070f3a541ed9cdf5a61f3ebe5",
        "int": 397,
        "// commented-key": "val",
        "object": {
            "key": 21
        },
        "array": [
            {
                "option31": "${ENV_VARIABLE1}",
                "option32": "${ENV_VARIABLE2}"
            }
        ],
        "nested": {
            "child1": {
                "child2": {
                    "env": "test_env_value_json2"
                }
            }
        }
    },
    "extra-config1": {},
    "extra-config2": {},
    "required": [
       "option1",
       "database"
    ]
}
```

## Examples

### Full usage

Assume we have a folder structure
```
your-app
├── composer.json
├── composer.lock
├── config
│   ├── _generated
│   ├── config.json
│   └── default.json
├── dump-config.php
└── vendor
    ├── autoload.php
    ├── bin
    ├── composer
    ├── fluix
    ├── readdle
    └── symfony
```

```php
<?php

require_once "vendor/autoload.php";

// Secret is a random string with length of 16 characters, which is used for encrypting/decrypting your secure configuration.
// Example of generating secret via openssl: openssl rand -hex 8
$secret = "ff7f8dc665734d9d"; // don't use this secret in your application, generate a new one and store it in a safe place.

$config = \Fluix\Config\Factory::config(
    $secret,
    "postProcessor"
);

$pathToConfig = __DIR__ . "/config/config.json";
$configFolder = __DIR__ . "/config/_generated";

$config->dump(
    \Fluix\Config\Source::fromPath($pathToConfig),
    $const = \Fluix\Config\Dump\Destination::create($configFolder, \Fluix\Config\Dump\Format::const()),
    $yaml = \Fluix\Config\Dump\Destination::create($configFolder, \Fluix\Config\Dump\Format::yaml()),
    $array = \Fluix\Config\Dump\Destination::create($configFolder, \Fluix\Config\Dump\Format::php()),
    $json = \Fluix\Config\Dump\Destination::create($configFolder, \Fluix\Config\Dump\Format::json())
);

echo "Config has been successfully dumped to {$const}" . PHP_EOL;
echo "Config has been successfully dumped to {$yaml}" . PHP_EOL;
echo "Config has been successfully dumped to {$array}" . PHP_EOL;
echo "Config has been successfully dumped to {$json}" . PHP_EOL;

function postProcessor(\Fluix\Config\ParserResult $result)
{
    // extra actions could be performed here
    // e.g. nginx.conf could be generated withing the parsed data
}
```

```bash
cd your-app
ENV_VARIABLE1=test_env ENV_VARIABLE2=test_env_2 php dump-config.php
```

Output:
```
Config has been successfully dumped to your-app/config/_generated/config.const.php
Config has been successfully dumped to your-app/config/_generated/config.parameters.yml
Config has been successfully dumped to your-app/config/_generated/config.array.php
Config has been successfully dumped to your-app/config/_generated/config.json

```

### Usage with fallback mechanism
You can use JSON config files as source of values as well.

Assume we have fallback.json in a root of the folder structure
```
your-app
├── composer.json
├── composer.lock
├── fallback.json
├── config
│   └── ...
└── ...
```

```php
<?php
require_once "vendor/autoload.php";
// Secret is a random string with length of 16 characters, which is used for encrypting/decrypting your secure configuration.
// Example of generating secret via openssl: openssl rand -hex 8
$secret             = "ff7f8dc665734d9d"; // don't use this secret in your application, generate a new one and store it in a safe place.
$pathToFallbackFile = __DIR__ . "/fallback.json";
$pathToConfig       = __DIR__ . "/config/config.json";
$configFolder       = __DIR__ . "/config/_generated";
$config = \Fluix\Config\Factory::fallbackConfig(
    $secret,
    Fluix\Config\File::fromPath($pathToFallbackFile),
    "postProcessor"
);
$config->dump(
    \Fluix\Config\Source::fromPath($pathToConfig),
    $const = \Fluix\Config\Dump\Destination::create($configFolder, \Fluix\Config\Dump\Format::const()),
    $yaml = \Fluix\Config\Dump\Destination::create($configFolder, \Fluix\Config\Dump\Format::yaml()),
    $array = \Fluix\Config\Dump\Destination::create($configFolder, \Fluix\Config\Dump\Format::php()),
    $json = \Fluix\Config\Dump\Destination::create($configFolder, \Fluix\Config\Dump\Format::json())
);
function postProcessor(\Fluix\Config\ParserResult $result)
{
    // extra actions could be performed here
    // e.g. nginx.conf could be generated withing the parsed data
}
```

```bash
echo "{\"ENV_VARIABLE2\":\"test_env_2\"}" > your-app/fallback.json
cd your-app
ENV_VARIABLE1=test_env php dump-config.php
```

Resulting folder structure
```
your-app
├── composer.json
├── composer.lock
├── {fallback.json}
├── config
│   ├── _generated
│   │   ├── config.array.php
│   │   ├── config.const.php
│   │   ├── config.json
│   │   └── config.parameters.yml
│   ├── config.json
│   └── default.json
├── dump-config.php
└── vendor
    ├── autoload.php
    ├── bin
    ├── composer
    ├── fluix
    ├── readdle
    └── symfony
```

Contents of `config.const.php` will be:
```php
<?php
// generated by Fluix\Config\Dump\PhpConstDumper, do not edit
define('option1', 'value1');
define('database', 'schema-overridden');
define('boolean', false);
define('null', NULL);
define('secret_value', 'secret-value-here');
define('int', 397);
define('object', array (
  'key' => 21,
));
define('array', array (
  0 => 
  array (
    'option31' => 'test_env',
  ),
));
define('nested', array (
  'child1' => 
  array (
    'child2' => 
    array (
      'env' => 'test_env_value_json2',
    ),
  ),
));
```

Contents of `config.array.php` will be:
```php
<?php
// generated by Fluix\Config\Dump\PhpDumper, do not edit
return array (
  'option1' => 'value1',
  'database' => 'schema-overridden',
  'boolean' => false,
  'null' => NULL,
  'secret_value' => 'secret-value-here',
  'int' => 397,
  'object' => 
  array (
    'key' => 21,
  ),
  'array' => 
  array (
    0 => 
    array (
      'option31' => 'test_env',
    ),
  ),
  'nested' => 
  array (
    'child1' => 
    array (
      'child2' => 
      array (
        'env' => 'test_env_value_json2',
      ),
    ),
  ),
);
```


Contents of `config.parameters.yml` will be:
```yaml
parameters:
    option1: value1
    database: schema-overridden
    boolean: false
    'null': null
    secret_value: secret-value-here
    int: 397
    object: { key: 21 }
    array: [{ option31: test_env }]
    nested: { child1: { child2: { env: test_env_value_json2 } } }
```

Contents of `config.json` will be:
```yaml
{
    "option1": "value1",
    "database": "schema-overridden",
    "boolean": false,
    "null": null,
    "secret_value": "secret-value-here",
    "int": 397,
    "object": {
        "key": 21
    },
    "array": [
        {
            "option31": "test_env"
        }
    ],
    "nested": {
        "child1": {
            "child2": {
                "env": "test_env_value_json2"
            }
        }
    }
}
```
