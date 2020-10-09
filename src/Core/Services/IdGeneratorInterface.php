<?php

declare(strict_types=1);

namespace InSided\GetOnBoard\Core\Services;

interface IdGeneratorInterface
{
    public function generate(): string;
}
