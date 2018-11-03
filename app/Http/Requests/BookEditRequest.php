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
            'authors' => [ 'required', 'max:255' ],
            'published_date' => [
                'required',
                'date_format:Y-m-d',
                // 今日以前の日付しか許容しない
                'before:' . date('Y-m-d', strtotime('+1 day')),
            ],
        ];
    }

    public function sanitize()
    {
        $input = $this->all();
        $input['volume'] = $input['volume'] ?? '';

        $this->replace($input);
    }
}
