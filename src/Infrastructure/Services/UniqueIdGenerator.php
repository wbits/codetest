<?php

declare(strict_types=1);

namespace InSided\GetOnBoard\Infrastructure\Services;

use InSided\GetOnBoard\Core\Services\IdGeneratorInterface;

class UniqueIdGenerator implements IdGeneratorInterface
{
    public function generate(): string
    {
        return uniqid();
    }
}
