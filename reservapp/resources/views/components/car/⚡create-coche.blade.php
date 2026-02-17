<?php

use Livewire\Component;

use App\Models\Coche;
use Livewire\WithFileUploads;

new class extends Component {
    use WithFileUploads;
    public ?Coche $car = null;
    public function mount(?Coche $car = null): void
    {
        $this->car = $car;
        if ($this->car) {
            $this->matricula = $this->car->matricula;
            $this->marca = $this->car->marca;
            $this->modelo = $this->car->modelo;
            $this->n_bastidor = $this->car->n_bastidor;
            $this->tipo_conbustible = $this->car->tipo_conbustible;
        }
    }

    //Crea un nuevo coche
    public function createCoche(): void
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

    //Actualiza un coche existente
    public function updateCoche(): void
    {
        // Validar los datos
        $validated = $this->validate([
            'matricula' => 'required|string|unique:coches,matricula,' . $this->car->id,
            'marca' => 'required|string|max:255',
            'modelo' => 'required|string|max:255',
            'n_bastidor' => 'required|string|unique:coches,n_bastidor,' . $this->car->id,
            'tipo_conbustible' => 'required|string|max:255',
            'img_vehiculo' => 'nullable|image|max:2048',
        ]);

        // Procesar la imagen si existe
        if ($this->img_vehiculo) {
            $validated['img_vehiculo'] = $this->img_vehiculo->store('vehiculos', 'public');
        }

        // Actualizar el coche
        $this->car->update($validated);

        // Resetear el formulario y cerrar el modal
        $this->resetForm();
        $this->showModal = false;

        // Disparar un evento para actualizar la lista de coches
        $this->dispatch('coche-actualizado');
    }
    
}
?>

<div>
    <h3>Crear Nuevo Coche</h3>
    <form action="">
        <flux:input
            name="matricula"
            :label="__('Matrícula')"
            :value="old('matricula', $car->matricula ?? '')"
            type="text"
            required
            autofocus
            placeholder="Matrícula del coche"
            type="text"
            wire:model="matricula"   
        />
        <flux:input
            name="marca"
            :label="__('Marca')"
            :value="old('marca', $car->marca ?? '')"
            type="text"
            required
            placeholder="Marca del coche"
            type="text"
            wire:model="marca"   
        />
        <flux:input
            name="modelo"
            :label="__('Modelo')"
            :value="old('modelo', $car->modelo ?? '')"
            type="text"
            required
            placeholder="Modelo del coche"
            type="text"
            wire:model="modelo"   
        />
        <flux:input
            name="n_bastidor"
            :label="__('Número de bastidor')"
            :value="old('n_bastidor', $car->n_bastidor ?? '')"
            type="text"
            required
            placeholder="Número de bastidor del coche"
            type="text"
            wire:model="n_bastidor"   
        />
        <flux:input
            name="tipo_conbustible"
            :label="__('Tipo de combustible')"
            :value="old('tipo_conbustible', $car->tipo_conbustible ?? '')"
            type="text"
            required
            placeholder="Tipo de combustible del coche"
            type="text"
            wire:model="tipo_conbustible"   
        />
        <flux:input
            name="img_vehiculo"
            :label="__('Foto del vehículo')"
            type="file"
            accept="image/*"
            wire:model="img_vehiculo"
        />
        @if($car->exists)
        <flux:input
            type="submit"
            value="Crear Coche"
            name= "crear_coche"
        />
        @endif
        @if(!$car->exists)
        <flux:input
            type="submit"
            value="Nuevo Coche"
            name= "nuevo_coche"
        />
        @endif
        

    </form>
</div>
