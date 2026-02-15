<?php

namespace App\Livewire\Coche;

use App\Models\Coche;
use Livewire\Component;
use Livewire\WithFileUploads;

/**
 * Componente Livewire para gestionar el modal de creación de coche
 *
 * Este componente maneja la apertura/cierre del modal y la validación del formulario
 */
class CreateCoche extends Component
{
    use WithFileUploads;

    public bool $showModal = false;

    // Propiedades del formulario
    public string $matricula = '';
    public string $marca = '';
    public string $modelo = '';
    public string $n_bastidor = '';
    public string $tipo_conbustible = '';
    public $img_vehiculo = null;

    /**
     * Abre el modal
     */
    public function openModal(): void
    {
        $this->showModal = true;
    }

    /**
     * Cierra el modal
     */
    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
    }

    /**
     * Reinicia el formulario
     */
    public function resetForm(): void
    {
        $this->matricula = '';
        $this->marca = '';
        $this->modelo = '';
        $this->n_bastidor = '';
        $this->tipo_conbustible = '';
        $this->img_vehiculo = null;
    }

    /**
     * Guarda el coche después de validar
     */
    public function saveCoche(): void
    {
        // Validar los datos
        $validated = $this->validate([
            'matricula' => 'required|string|unique:coches,matricula',
            'marca' => 'required|string|max:255',
            'modelo' => 'required|string|max:255',
            'n_bastidor' => 'required|string|unique:coches,n_bastidor',
            'tipo_conbustible' => 'required|string|max:255',
            'img_vehiculo' => 'nullable|image|max:2048',
        ]);

        // Procesar la imagen si existe
        if ($this->img_vehiculo) {
            $validated['img_vehiculo'] = $this->img_vehiculo->store('vehiculos', 'public');
        }

        // Crear el coche
        Coche::create($validated);

        // Resetear el formulario y cerrar el modal
        $this->resetForm();
        $this->showModal = false;

        // Disparar un evento para actualizar la lista de coches
        $this->dispatch('coche-creado');
    }

    public function render()
    {
        return view('livewire.coche.create-coche');
    }
}
