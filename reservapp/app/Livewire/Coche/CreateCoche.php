<?php

namespace App\Livewire\Coche;

use Livewire\Component;

/**
 * Componente Livewire para gestionar el modal de creación de coche
 *
 * Este componente solo maneja la apertura/cierre del modal.
 * La lógica de creación se delega al controlador CocheController@store
 */
class CreateCoche extends Component
{
    public bool $showModal = false;

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
    }

    public function render()
    {
        return view('livewire.coche.create-coche');
    }
}
