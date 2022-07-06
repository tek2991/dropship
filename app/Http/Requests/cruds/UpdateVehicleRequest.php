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
        if ($this->user()->hasRole('admin')) {
            return true;
        }

        if ($this->user()->hasRole('manager')) {
            // check if manager is assigned to this vehicles locations
            $vehicle_location_ids = $this->vehicle->locations->pluck('id')->toArray();
            $manager_location_ids = $this->user()->manager->locations->pluck('id')->toArray();
            return count(array_intersect($vehicle_location_ids, $manager_location_ids)) > 0;
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
