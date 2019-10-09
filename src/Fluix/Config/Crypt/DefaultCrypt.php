<?php

declare(strict_types = 1);

namespace Fluix\Config\Crypt;

use Fluix\Config\Crypt;

class DefaultCrypt implements Crypt
{
    private $secret;
    
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
