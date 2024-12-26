<?php

namespace Tests\Feature\Api\V1\Users;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class RegisterUserTest extends TestCase
{
    use RefreshDatabase;

    private const ENDPOINT = '/api/V1/users';

    private const VALID_USERNAME = 'blackWolf';

    private const VALID_EMAIL = 'test@gmail.com';

    private const VALID_PASSWORD = 'A$trongPassword123';

    public function testRegisterUser(): void
    {
        $data = [
            'username' => self::VALID_USERNAME,
            'email' => self::VALID_EMAIL,
            'password' => self::VALID_PASSWORD,
        ];

        $response = $this->postJson(self::ENDPOINT, $data);
        unset($data['password']);

        $response->assertCreated();
        $response->assertJsonFragment($data);
    }

    /**
     * @param array<string, array<string, array<string, string>>> $data
     */
    #[DataProvider('validateDataProvider')]
    public function testRegisterUserValidation(array $data, string $validationField): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson(self::ENDPOINT, $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors($validationField);
    }

    /**
     * @return array<string, array<string, array<string, string>|string>>
     */
    public static function validateDataProvider(): array
    {
        return [
            'missing: username' => [
                'data' => [
                    'email' => self::VALID_EMAIL,
                    'password' => self::VALID_PASSWORD,
                ],
                'validationField' => 'username',
            ],
            'missing: email' => [
                'data' => [
                    'username' => self::VALID_USERNAME,
                    'password' => self::VALID_PASSWORD,
                ],
                'validationField' => 'email',
            ],
            'missing: password' => [
                'data' => [
                    'username' => self::VALID_USERNAME,
                    'email' => self::VALID_EMAIL,
                ],
                'validationField' => 'password',
            ],
            'long: username' => [
                'data' => [
                    'username' => str_repeat('b', 55),
                    'email' => self::VALID_EMAIL,
                    'password' => self::VALID_PASSWORD,
                ],
                'validationField' => 'username',
            ],
            'long: email' => [
                'data' => [
                    'username' => self::VALID_USERNAME,
                    'email' => str_repeat('b', 257),
                    'password' => self::VALID_PASSWORD,
                ],
                'validationField' => 'email',
            ],
            'short: password' => [
                'data' => [
                    'username' => self::VALID_USERNAME,
                    'email' => self::VALID_EMAIL,
                    'password' => 'aA1',
                ],
                'validationField' => 'password',
            ],
        ];
    }
}
