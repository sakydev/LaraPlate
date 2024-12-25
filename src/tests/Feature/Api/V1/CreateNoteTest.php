<?php

namespace Tests\Feature\Api\V1;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class CreateNoteTest extends TestCase
{
    use RefreshDatabase;

    private const string ENDPOINT = '/api/V1/notes';

    public function testCreateNote(): void
    {
        $user = User::factory()->create();
        $data = [
            'name' => 'Test Note',
            'content' => 'This is a test note',
        ];

        $response = $this->actingAs($user)->postJson(self::ENDPOINT, $data);

        $response->assertCreated();
        $response->assertJsonFragment($data);
    }

    #[DataProvider('validateDataProvider')]
    public function testCreateNoteValidation(array $data, string $validationField): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson(self::ENDPOINT, $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors($validationField);
    }

    public static function validateDataProvider(): array
    {
        return [
            'missing: name' => [
                'data' => [
                    'content' => 'This is a test note',
                ],
                'validationField' => 'name',
            ],
            'missing: content' => [
                'data' => [
                    'name' => 'Test Note',
                ],
                'validationField' => 'content',
            ],
        ];
    }
}
