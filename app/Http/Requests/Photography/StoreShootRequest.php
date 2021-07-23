<?php

namespace App\Http\Requests\Photography;

use Illuminate\Foundation\Http\FormRequest;

class StoreShootRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'date' => 'date_format:Y-m-d|nullable',
            'desc' => 'string|nullable',
        ];
    }
}
