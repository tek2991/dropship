<?php

namespace App\Http\Requests\cruds;

use Illuminate\Validation\Rule;
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
        return $this->user()->hasRole('admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|regex:/^[a-zA-Z0-9_\s]+$/|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($this->transporter->user_id),
            ],
            'address' => 'required|string',
            'phone' => [
                'required',
                'numeric',
                Rule::unique('users', 'phone')->ignore($this->transporter->user_id),
            ],
            'alternate_phone' => 'nullable|numeric',
            'is_first_party' => 'required|boolean',
            'is_active' => 'required|boolean',
        ];
    }
}
