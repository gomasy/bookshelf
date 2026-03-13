<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CorrectCheckDigit implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!\NDL::verifyCheckDigit($value)) {
            $fail('Invalid check digit.');
        }
    }
}
