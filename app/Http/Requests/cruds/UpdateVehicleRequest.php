<?php

namespace App\Http\Requests\cruds;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateVehicleRequest extends FormRequest
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
            'registration_number' => [
                'required',
                'alpha_num',
                'min:8',
                'max:12',
                Rule::unique('vehicles', 'registration_number')->ignore($this->vehicle->id),
            ],
            'is_active' => 'required|boolean',
        ];
    }
}
