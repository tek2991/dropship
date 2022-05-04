<?php

namespace App\Http\Requests\cruds;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateLogSheetRequest extends FormRequest
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
            'driver_id' => 'required|exists:drivers,id',
            'transporter_id' => 'required|exists:transporters,id',
            'vehicle_id' => 'required|exists:vehicles,id',
        ];
    }
}
