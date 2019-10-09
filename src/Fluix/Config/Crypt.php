<?php

declare(strict_types = 1);

namespace Fluix\Config;

interface Crypt
{
    public function encrypt(string $value): string;
    
    public function decrypt(string $value): string;
}
