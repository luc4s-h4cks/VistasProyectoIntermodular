<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TallerRequest extends FormRequest
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

        $tallerId = $this->route('taller')?->id_taller ?? auth()->user()?->taller?->id_taller;

        return [
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',

            'ubicacion' => 'required|string|max:255',

            'handle' => [
                'required',
                'string',
                'max:50',
                Rule::unique('taller', 'handle')
                    ->ignore($tallerId, 'id_taller'),
            ],

            'imagen_taller' => 'nullable|image|max:2048',
            'imagen_contacto' => 'nullable|image|max:2048',

            'servicios' => 'required|array|min:1',
            'servicios.*' => 'string',

            'vehiculos' => 'required|array|min:1',
            'vehiculos.*' => 'string',

            'info_contacto' => 'nullable|string',

            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
        ];

    }

    public function messages(): array
    {
        return [

            'nombre.required' => 'El nombre del taller es obligatorio.',
            'nombre.max' => 'El nombre no puede superar los 255 caracteres.',

            'descripcion.required' => 'Debes añadir una descripción del taller.',

            'ubicacion.required' => 'La ubicación es obligatoria.',

            'handle.required' => 'El handle es obligatorio.',
            'handle.unique' => 'Ese handle ya está en uso.',
            'handle.max' => 'El handle no puede superar los 50 caracteres.',

            'imagen_taller.image' => 'La imagen del taller debe ser una imagen válida.',
            'imagen_taller.max' => 'La imagen del taller no puede superar 2MB.',

            'imagen_contacto.image' => 'La imagen de contacto debe ser una imagen válida.',
            'imagen_contacto.max' => 'La imagen de contacto no puede superar 2MB.',

            'servicios.required' => 'Debes seleccionar al menos un servicio.',
            'servicios.min' => 'Debes seleccionar al menos un servicio.',

            'vehiculos.required' => 'Debes seleccionar al menos un tipo de vehículo.',
            'vehiculos.min' => 'Debes seleccionar al menos un tipo de vehículo.',

            'email.email' => 'El email no tiene un formato válido.',
        ];
    }

    public function attributes(): array
    {
        return [
            'nombre' => 'nombre del taller',
            'descripcion' => 'descripción',
            'ubicacion' => 'ubicación',
            'handle' => 'handle',
            'servicios' => 'servicios',
            'vehiculos' => 'vehículos',
            'telefono' => 'teléfono',
            'email' => 'correo electrónico',
        ];
    }
}
