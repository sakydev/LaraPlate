<?php

namespace Tests\Feature\Api\V1\Notes;

use App\Models\Note;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListNoteTest extends TestCase
{
    use RefreshDatabase;

    private const ENDPOINT = '/api/V1/notes';

    public function testListNotes(): void
    {
        $user = User::factory()->create();
        $notes = $user->notes()->createMany(
            Note::factory()->count(5)->make()->toArray()
        );

        $response = $this->actingAs($user)->getJson(self::ENDPOINT);

        $response->assertOk();
        $response->assertJsonCount(5, 'content.notes');

        /** @phpstan-ignore-next-line */
        $response->assertJsonFragment($notes->first()->toArray());
    }
}
