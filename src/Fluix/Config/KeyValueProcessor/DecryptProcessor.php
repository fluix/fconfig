<?php

declare(strict_types = 1);

namespace Fluix\Config\KeyValueProcessor;

use Fluix\Config\Crypt;
use Fluix\Config\KeyProcessor;
use Fluix\Config\ValueProcessor;

final class DecryptProcessor implements ValueProcessor, KeyProcessor
{
    private Crypt $crypt;
    
    public function __construct(Crypt $crypt)
    {
        $this->crypt = $crypt;
    }
    
    public function process(string $value): string
    {
        return $this->crypt->decrypt($value);
    }
}
