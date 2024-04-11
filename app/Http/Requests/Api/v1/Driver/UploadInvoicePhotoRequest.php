<?php

namespace App\Http\Requests\Api\v1\Driver;

use Illuminate\Foundation\Http\FormRequest;

class UploadInvoicePhotoRequest extends FormRequest
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
            'image' => 'bail|required|file|mimes:jpeg,png,jpg,gif,svg|max:5120',
        ];
    }
}
