<?php

declare(strict_types = 1);

namespace Fluix\Config\Crypt;

use Fluix\Config\Crypt;

final class DefaultCrypt implements Crypt
{
    private Secret $secret;
    
    public function __construct(Secret $secret)
    {
        $this->secret = $secret;
    }
    
    public function encrypt(string $value): string
    {
        return \Readdle\Crypt\Crypto::encrypt($value, (string)$this->secret);
    }
    
    public function decrypt(string $value): string
    {
        return \Readdle\Crypt\Crypto::decrypt($value, (string)$this->secret);
    }
}
