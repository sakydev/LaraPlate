<?php

namespace App\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function rules()
    {
        return [
            'status' => ['sometimes', 'required', 'string', 'in:active,inactive'],
            'username' => ['sometimes', 'required', 'string'],
            'level' => ['sometimes', 'required', 'int', 'in:1,2,3,4,5'],
        ];
    }
}
