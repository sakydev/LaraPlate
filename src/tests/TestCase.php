<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * @param array<string, mixed> $response
     * */
    public function assertSuccess(array $response): void
    {
        static::assertTrue($response['success']);
        static::assertNull($response['errors']);
    }

    /**
     * @param array<string, mixed> $response
     * */
    public function assertError(array $response): void
    {
        static::assertNull($response['success']);
        static::assertNotEmpty($response['errors']);
    }
}
