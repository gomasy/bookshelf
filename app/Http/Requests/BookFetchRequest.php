<?php
declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

use App\Rules\CheckFetchType;
use App\Rules\CorrectCheckDigit;

class BookFetchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'context' => [
                'required',
                new CheckFetchType($this->query->get('type')),
                new CorrectCheckDigit,
            ],
            'type' => [
                'required',
                'in:code,title',
            ],
        ];
    }
}
