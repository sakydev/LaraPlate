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

    private const string VALID_NAME = 'Test Note';

    private const string VALID_CONTENT = 'This is a test note';

    public function testCreateNote(): void
    {
        $user = User::factory()->create();
        $data = [
            'name' => self::VALID_NAME,
            'content' => self::VALID_CONTENT,
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
                    'content' => self::VALID_CONTENT,
                ],
                'validationField' => 'name',
            ],
            'missing: content' => [
                'data' => [
                    'name' => self::VALID_NAME,
                ],
                'validationField' => 'content',
            ],
            'long: name' => [
                'data' => [
                    'name' => str_repeat('a', 256),
                    'content' => self::VALID_CONTENT,
                ],
                'validationField' => 'name',
            ],
        ];
    }
}
