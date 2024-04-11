<?php

namespace App\Http\Requests\cruds;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateDriverRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->user()->hasRole('admin')) {
            return true;
        }

        if ($this->user()->hasRole('manager')) {
            // check if manager is assigned to this drivers locations
            $driver_location_ids = $this->driver->locations->pluck('id')->toArray();
            $manager_location_ids = $this->user()->manager->locations->pluck('id')->toArray();
            return count(array_intersect($driver_location_ids, $manager_location_ids)) > 0;
        }

        return false;
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
                Rule::unique('users', 'email')->ignore($this->driver->user_id, 'id'),
            ],
            'gender' => 'nullable|in:male,female',
            'dob' => 'nullable|date',
            'address' => 'required|string',
            'phone' => [
                'required',
                'numeric',
                Rule::unique('users')->ignore($this->driver->user_id, 'id'),
            ],
            'alternate_phone' => 'nullable|numeric',
            'is_active' => 'required|boolean',
        ];
    }
}
