<?php

namespace Tests\Feature\Api\V1\Notes;

use App\Models\Note;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShowNoteTest extends TestCase
{
    use RefreshDatabase;

    private const ENDPOINT = '/api/V1/notes/%d';

    public function testShowNote(): void
    {
        $user = User::factory()->create();

        /** @var Note $note */
        $note = $user->notes()->create(
            Note::factory()->make()->toArray()
        );

        $requestUrl = sprintf(self::ENDPOINT, $note->id);
        $response = $this->actingAs($user)->getJson($requestUrl);

        $response->assertOk();
        $response->assertJsonFragment($note->toArray());
    }

    public function testShowNoteNotFound(): void
    {
        $user = User::factory()->create();

        $requestUrl = sprintf(self::ENDPOINT, 1);
        $response = $this->actingAs($user)->getJson($requestUrl);

        $response->assertNotFound();
    }
}
