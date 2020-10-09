<?php

declare(strict_types=1);

namespace InSided\GetOnBoard\Test\Unit\Infrastructure;

use InSided\GetOnBoard\Infrastructure\Services\UniqueIdGenerator;
use PHPUnit\Framework\TestCase;

class UniqueIdGeneratorTest extends TestCase
{
    public function testGeneratedIdIsAString(): void
    {
        $generator = new UniqueIdGenerator();
        $id = $generator->generate();

        $this->assertIsString($id);
    }
}
