<?php

namespace Fluix\Config;

use Fluix\Config\Exception\Exception;

interface Parser
{
    /**
     * @throws Exception
     */
    public function parse(Source ...$configs): array;
}
