<?php
declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

use Facades\App\Libs\NDL;

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
     * Determine if the request passes the authorization check.
     *
     * @return bool
     */
    public function passesAuthorization(): bool
    {
        parent::passesAuthorization();

        if (!isset($this->code) || !NDL::verifyCheckDigit($this->code)) {
            throw new ValidationException($this->getValidatorInstance());
        }

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
            'code' => [ 'required', 'regex:/^(.{8}|.{10}|.{13})$/' ],
        ];
    }
}
