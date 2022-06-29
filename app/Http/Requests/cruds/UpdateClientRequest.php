<?php

namespace App\Http\Requests\cruds;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateClientRequest extends FormRequest
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
                Rule::unique('users', 'email')->ignore($this->client->user_id),
            ],
            'gender' => 'nullable|in:male,female',
            'dob' => 'nullable|date',
            'address' => 'required|string',
            'phone' => [
                'required',
                'numeric',
                Rule::unique('users', 'phone')->ignore($this->client->user_id),
            ],
            'alternate_phone' => 'nullable|numeric',
            'is_active' => 'required|boolean',
            'client_number' => [
                'required',
                'alpha_num',
                Rule::unique('clients', 'client_number')->ignore($this->client->id),
            ],
        ];
    }
}
