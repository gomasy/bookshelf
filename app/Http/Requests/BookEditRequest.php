<?php
declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BookEditRequest extends FormRequest
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
        $this->sanitize();

        return [
            'title' => [ 'required', 'max:255' ],
            'volume' => [ 'max:255' ],
            'authors' => [ 'max:255' ],
            'price' => [ 'max:255' ],
            'publisher' => [ 'max:255' ],
            'status_id' => [ 'required', 'numeric' ],
        ];
    }

    public function sanitize()
    {
        $input = $this->all();
        $input['volume'] = $input['volume'] ?? '';

        $this->replace($input);
    }
}
