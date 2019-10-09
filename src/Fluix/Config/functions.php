<?php

declare(strict_types=1);
// phpcs:disable Squiz.Classes.ClassFileName.NoMatch
// phpcs:disable PHPCompatibility.Constants.NewConstants.json_throw_on_errorFound
// phpcs:disable PHPCompatibility.Classes.NewClasses.jsonexceptionFound
// phpcs:disable PSR1.Classes.ClassDeclaration.MultipleClasses

namespace {
    if (!class_exists("JsonException")) {
        class JsonException extends \Exception
        {
        
        }
    }
}

namespace Fluix\Config {
    if (!class_exists("\Fluix\Config\JsonException")) {
        class JsonException extends \JsonException
        {
            public static function createFromErrorCode(int $errorCode, ?\Throwable $previous = null): self
            {
                switch ($errorCode) {
                    case JSON_ERROR_DEPTH:
                        return new self("The maximum stack depth has been exceeded", 0, $previous);
                    case JSON_ERROR_STATE_MISMATCH:
                        return new self("Invalid or malformed JSON", 0, $previous);
                    case JSON_ERROR_CTRL_CHAR:
                        return new self("Control character error, possibly incorrectly encoded", 0, $previous);
                    case JSON_ERROR_SYNTAX:
                        return new self("Syntax error", 0, $previous);
                    case JSON_ERROR_UTF8:
                        return new self("Malformed UTF-8 characters, possibly incorrectly encoded", 0, $previous);
                    case JSON_ERROR_RECURSION:
                        return new self("One or more recursive references in the value to be encoded", 0, $previous);
                    case JSON_ERROR_INF_OR_NAN:
                        return new self("One or more NAN or INF values in the value to be encoded", 0, $previous);
                    case JSON_ERROR_UNSUPPORTED_TYPE:
                        return new self("A value of a type that cannot be encoded was given", 0, $previous);
                    case JSON_ERROR_INVALID_PROPERTY_NAME:
                        return new self("A property name that cannot be encoded was given", 0, $previous);
                    case JSON_ERROR_UTF16:
                        return new self("Malformed UTF-16 characters, possibly incorrectly encoded", 0, $previous);
                    default:
                        return new self("Undefined error: '{$errorCode}'", 0, $previous);
                }
            }
        }
    }
    
    if (function_exists("\Fluix\Config\json_decode")) {
        return;
    }
    
    if (PHP_VERSION_ID < 70300) {
        /**
         * @throws \JsonException
         */
        function json_decode(string $json, bool $assoc = false, int $depth = 512, int $options = 0)
        {
            $result = @\json_decode($json, $assoc, $depth, $options);
            $lastError = \json_last_error();
            if (JSON_ERROR_NONE == $lastError) {
                return $result;
            }
            throw JsonException::createFromErrorCode($lastError);
        }
    } else {
        /**
         * @throws \JsonException
         */
        function json_decode(string $json, bool $assoc = false, int $depth = 512, int $options = 0)
        {
            return \json_decode($json, $assoc, $depth, $options | JSON_THROW_ON_ERROR);
        }
    }
}
