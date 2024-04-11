<?php

namespace App\Http\Requests\Api\v1\Driver;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInvoiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->driver->invoices->contains($this->invoice);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'delivery_status' => 'required|string|in:delivered,pending,cancelled',
            'remarks' => 'required|string|max:255',
        ];
    }
}
