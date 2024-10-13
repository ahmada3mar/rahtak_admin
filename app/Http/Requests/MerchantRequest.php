<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MerchantRequest extends FormRequest
{

    protected function prepareForValidation()
    {
        $this->merge([
            'raw' => $this->raw ?? false,
            'push_only' => $this->push_only ?? false,
            'json_format' => $this->json_format ?? false,
            'exclude_pending' => $this->exclude_pending ?? false,
            'custom_handler' => $this->custom_handler ?? false,
            'webhook_url' => $this->webhook_url ?? false,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|unique:merchants,name,' . $this->id,
            'raw' => 'required|boolean',
            'push_only' => 'required|boolean',
            'json_format' => 'required|boolean',
            'exclude_pending' => 'required|boolean',
            'custom_handler' => 'required|boolean',
            'webhook_url' => 'required|nullable|active_url',
            'api_key' => 'required',
            'id' => 'nullable',
            'headers' => 'nullable|array',
            'headers.*.value' => 'required',
            'headers.*.key' => 'required|distinct|regex:/^[a-zA-Z-0-9]+$/',
            'need_certificate' => 'string|in:true,false',
            'certificate' => 'file|required_if:need_certificate,true'
        ];
    }
}
