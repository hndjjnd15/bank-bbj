<?php

namespace App\Rules;

use App\Models\VideoYoutube;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class YoutubeVideoUrl implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (VideoYoutube::idDariUrl(is_string($value) ? $value : null) === null) {
            $fail('Masukkan tautan video YouTube yang valid.');
        }
    }
}
