<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CheckFetchType implements ValidationRule
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
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($this->type === 'code' && !ctype_digit($value)) {
            if (isset($value[9]) && $value[9] === 'X') {
                return;
            }

            $fail('検索タイプが不正です。');
        }
    }
}
