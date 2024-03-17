<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSoftwareRequest extends FormRequest
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
        return [
            'sku' => 'required|string|min:10|max:10|unique:software,sku,',
            'nombre' => 'required|string|max:55',
            'identificador_tipo_software' => 'required|integer|exists:software_types,id',
            'identificador_sistema_operativo' => 'required|integer|exists:operating_systems,id',
            'precio' => 'required|decimal:2,2',
            'cantidad' => 'required|integer',
        ];
    }
}
