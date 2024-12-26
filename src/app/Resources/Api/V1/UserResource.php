<?php

declare(strict_types=1);

namespace App\Resources\Api\V1;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    private bool $includeToken;

    public function __construct($resource, bool $includeToken = false)
    {
        parent::__construct($resource);

        $this->includeToken = $includeToken;
    }

    /**
     * Transform the resource into an array.
     *
     * @param  Request $request
     * @return array<string, mixed>
     */
    public function toArray(?Request $request = null): array
    {
        /** @var User $this*/
        $data = [
            'id' => $this->id,
            'username' => $this->username,
            'email' => $this->email,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

        if ($this->includeToken) {
            $data['_token'] = $this->createToken('auth_token')->plainTextToken;
        }

        return $data;
    }
}
