<?php

namespace App\Http\Requests\cruds;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTransporterRequest extends FormRequest
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
            'name' => 'required|regex:/^[a-zA-Z0-9\s]+$/|max:255',
            'address' => 'required|string',
            'phone' => 'required|string|max:16',
            'alternate_phone' => 'nullable|string|max:16',
            'is_first_party' => 'required|boolean',
            'is_active' => 'required|boolean',
        ];
    }
}
