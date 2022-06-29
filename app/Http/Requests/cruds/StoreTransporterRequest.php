<?php

namespace App\Http\Requests\cruds;

use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class StoreTransporterRequest extends FormRequest
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
            'email' => 'required|email|unique:users,email',
            'address' => 'required|string',
            'phone' => 'required|numeric|unique:users,phone',
            'alternate_phone' => 'nullable|numeric',
            'password' => ['required', 'confirmed', Password::defaults()],
            'is_first_party' => 'required|boolean',
        ];
    }
}
