<?php

namespace App\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class CreateNoteRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string', 'max:5000'],
            'status' => ['sometimes', 'required', 'string', 'in:active,inactive'],
        ];
    }
}
