<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLicenseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $licenseId = $this->route('license')->id;

        return [
            'identificador_software' => 'nullable|integer|exists:software,id',
            'serial' => 'nullable|string|min:100|max:100|unique:licenses,serial,' . $licenseId,
        ];
    }
}
