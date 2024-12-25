<?php

namespace Tests\Feature\Api\V1;

use App\Models\Note;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
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
        $response->assertJsonFragment($notes->first()->toArray());
    }
}
