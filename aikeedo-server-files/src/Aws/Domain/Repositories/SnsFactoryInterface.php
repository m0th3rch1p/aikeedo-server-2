<?php

namespace Aws\Domain\Repositories;

use Aws\Infrastructure\Sns\Services\SnsBaseService;

interface SnsFactoryInterface
{
    public function register(string $service);
}