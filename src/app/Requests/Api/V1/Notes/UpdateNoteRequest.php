<?php

namespace App\Requests\Api\V1\Notes;

use Illuminate\Foundation\Http\FormRequest;

class UpdateNoteRequest extends FormRequest
{
    /** @return array<string, array<int, string>> */
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'content' => ['sometimes', 'required', 'string', 'max:5000'],
            'status' => ['sometimes', 'required', 'string', 'in:published,draft'],
        ];
    }
}
