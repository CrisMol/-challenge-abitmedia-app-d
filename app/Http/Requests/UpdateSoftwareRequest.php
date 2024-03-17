<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSoftwareRequest extends FormRequest
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
        $softwareId = $this->route('software')->id;

        return [
            'sku' => 'nullable|string|min:10|max:10|unique:software,sku,' . $softwareId,
            'nombre' => 'nullable|string|max:55',
            'identificador_tipo_software' => 'nullable|integer|exists:software_types,id',
            'identificador_sistema_operativo' => 'nullable|integer|exists:operating_systems,id',
            'precio' => 'nullable|decimal:2,2',
            'cantidad' => 'nullable|integer',
        ];
    }
}
