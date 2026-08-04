<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class NoScriptInImage implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param string $attribute
     * @param mixed $value
     * @param Closure(string): PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            $data = file_get_contents($value->getPathname());
            $base64 = base64_encode($data);
            $bin = base64_decode($base64);
            $im = imageCreateFromString($bin);
        } catch (\Exception $e) {
            $fail('The uploaded image is not valid, containing scripts!');
        }
    }
}
