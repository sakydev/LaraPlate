<?php

namespace Tests\Feature\Api\V1\Notes;

use App\Models\Note;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class UpdateNoteTest extends TestCase
{
    use RefreshDatabase;

    private const ENDPOINT = '/api/V1/notes/%d';

    private const VALID_NAME = 'Test Note';

    private const VALID_CONTENT = 'This is a test note';

    public function testUpdateNote(): void
    {
        $user = User::factory()->create();

        /** @var Note $note */
        $note = $user->notes()->create(
            Note::factory()->make()->toArray()
        );

        $data = [
            'name' => self::VALID_NAME,
            'content' => self::VALID_CONTENT,
        ];

        $requestUrl = sprintf(self::ENDPOINT, $note->id);
        $response = $this->actingAs($user)->putJson($requestUrl, $data);

        $response->assertOk();
        $response->assertJsonFragment($data);
    }

    public function testCanOnlyUpdateOwnNote(): void
    {
        $user = User::factory()->create();
        $note = Note::factory()->create();

        $data = [
            'name' => self::VALID_NAME,
            'content' => self::VALID_CONTENT,
        ];

        $requestUrl = sprintf(self::ENDPOINT, $note->id);
        $response = $this->actingAs($user)->putJson($requestUrl, $data);

        $response->assertForbidden();
    }

    public function testCanOnlyUpdateExistingNote(): void
    {
        $user = User::factory()->create();

        $data = [
            'name' => self::VALID_NAME,
            'content' => self::VALID_CONTENT,
        ];

        $requestUrl = sprintf(self::ENDPOINT, time());
        $response = $this->actingAs($user)->putJson($requestUrl, $data);

        $response->assertNotFound();
        $this->assertError($response->json());
    }

    /**
     * @param array<string, array<string, array<string, string>|string>> $data
     */
    #[DataProvider('validateDataProvider')]
    public function testUpdateNoteValidation(array $data, string $validationField): void
    {
        $user = User::factory()->create();

        /** @var Note $note */
        $note = $user->notes()->create(
            Note::factory()->make()->toArray()
        );

        $requestUrl = sprintf(self::ENDPOINT, $note->user_id);
        $response = $this->actingAs($user)->putJson($requestUrl, $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors($validationField);

        $this->assertError($response->json());
    }

    /**
     * @return array<string, array<string, array<string, string>|string>>
     */
    public static function validateDataProvider(): array
    {
        return [
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
