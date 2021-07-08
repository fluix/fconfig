<?php

declare(strict_types = 1);

namespace Fluix\Config;

use Fluix\Config\Exception\Exception;

interface Reader
{
    /**
     * @throws Exception
     */
    public function read(File $source): array;
    
    public function supports(File $source): bool;
}
