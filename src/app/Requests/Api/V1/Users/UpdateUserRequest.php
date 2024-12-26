<?php

namespace App\Requests\Api\V1\Users;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    /** @return array<string, array<int, string>> */
    public function rules(): array
    {
        return [
            'status' => ['sometimes', 'required', 'string', 'in:active,inactive'],
            'username' => ['sometimes', 'required', 'string'],
            'level' => ['sometimes', 'required', 'int', 'in:1,2,3,4,5'],
        ];
    }
}
