<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CorrectCheckDigit implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return \NDL::verifyCheckDigit($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Invalid check digit.';
    }
}
