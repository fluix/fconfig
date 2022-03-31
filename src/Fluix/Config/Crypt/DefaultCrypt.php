<?php

declare(strict_types = 1);

namespace Fluix\Config\Crypt;

use Fluix\Config\Crypt;
use Readdle\Crypt\CryptoInterface;

final class DefaultCrypt implements Crypt
{
    private CryptoInterface $crypto;

    public function __construct(CryptoInterface $crypto)
    {
        $this->crypto = $crypto;
    }
    
    public function encrypt(string $value): string
    {
        return $this->crypto->encrypt($value);
    }
    
    public function decrypt(string $value): string
    {
        return $this->crypto->decrypt($value);
    }
}
