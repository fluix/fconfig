<?php

declare(strict_types = 1);

namespace Fluix\Config;

interface PreProcessorInterface
{
    public function preprocess(array $config): array;
}
