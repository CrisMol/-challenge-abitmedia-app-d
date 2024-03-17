<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateServiceRequest extends FormRequest
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
        $serviceId = $this->route('service')->id;

        return [ 
            'sku' => 'required|string|min:10|max:10|unique:services,sku,' . $serviceId,
            'nombre' => 'required|string|max:55',
            'precio' => 'required|decimal:2,2',
            'estado' => 'nullable|in:0,1',
        ];
    }
}
