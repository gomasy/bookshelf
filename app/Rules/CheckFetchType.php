<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CheckFetchType implements Rule
{
    /**
     * Fetch type
     *
     * @var string
     */
    protected $type = '';

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(string $type)
    {
        $this->type = $type;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if ($this->type === 'code' && !ctype_digit($value)) {
            if (isset($value[9]) && $value[9] === 'X') {
                return true;
            }

            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return '検索タイプが不正です。';
    }
}
