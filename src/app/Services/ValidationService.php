<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Validator;

class ValidationService
{
    protected function validateRules(array $input, array $rules): ?array
    {
        $validator = Validator::make($input, $rules);
        $errors = [];
        if ($validator->fails()) {
            foreach ($validator->messages()->get('*') as $title => $description) {
                $errors[] = [
                    'title' => $title,
                    'description' => current($description),
                ];
            }

            return $errors;
        }

        return null;
    }
}
