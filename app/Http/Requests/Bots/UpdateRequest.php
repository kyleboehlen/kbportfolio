<?php

namespace App\Http\Requests\Bots;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
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
            'img' => 'mimes:jpg,jpeg,png',
            'desc' => 'required|string',
            'client-id' => 'required|integer',
            'permissions' => 'integer|nullable',
            'scope' => 'required',
        ];
    }
}
