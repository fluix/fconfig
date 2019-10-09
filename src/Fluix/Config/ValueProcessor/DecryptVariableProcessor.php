<?php

declare(strict_types = 1);

namespace Fluix\Config\ValueProcessor;

use Fluix\Config\Crypt;
use Fluix\Config\ValueProcessor;

final class DecryptVariableProcessor implements ValueProcessor
{
    private $crypt;
    
    public function __construct(Crypt $crypt)
    {
        $this->crypt = $crypt;
    }
    
    public function process(string $value): string
    {
        return $this->crypt->decrypt($value);
    }
}
