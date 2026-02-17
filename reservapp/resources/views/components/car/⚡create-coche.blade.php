<?php

use Livewire\Component;

use App\Models\Coche;
use Livewire\WithFileUploads;

new class extends Component
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
        
    }
};
?>

<div>
    {{-- Botón para abrir modal --}}
    <flux:button wire:click="openModal" variant="primary">
        {{ __('Nuevo coche') }}
    </flux:button>

    {{-- Modal --}}
    <flux:modal wire:model="showModal" name="create-coche" focusable class="max-w-2xl">
        <div class="p-6 space-y-6">
            <div class="flex items-center justify-between border-b pb-4">
                <flux:heading size="lg">{{ __('Nuevo coche') }}</flux:heading>
            </div>
            <form wire:submit="saveCoche" class="space-y-6">
                @csrf
                @method('POST')
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <flux:input
                            type="text"
                            wire:model="matricula"
                            :label="__('Matrícula')"
                            required
                        />
                        @error('matricula')
                            <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <flux:input
                            type="text"
                            wire:model="marca"
                            :label="__('Marca')"
                            required
                        />
                        @error('marca')
                            <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <flux:input
                            type="text"
                            wire:model="modelo"
                            :label="__('Modelo')"
                            required
                        />
                        @error('modelo')
                            <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <flux:input
                            type="text"
                            wire:model="n_bastidor"
                            :label="__('Número de bastidor')"
                            required
                        />
                        @error('n_bastidor')
                            <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="border-t pt-4 space-y-3">
                    <label class="text-red-600 font-medium text-sm">{{ __('Cambiar foto Coche') }}</label>

                    <div class="bg-gray-400 rounded-lg h-48 flex items-center justify-center text-white">
                        <span class="text-lg">{{ __('Foto vehículo') }}</span>
                    </div>

                    <div>
                        <flux:input
                            type="file"
                            wire:model="img_vehiculo"
                            accept="image/*"
                            :label="__('Seleccionar imagen')"
                        />
                        @error('img_vehiculo')
                            <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <flux:input
                            type="text"
                            wire:model="tipo_conbustible"
                            :label="__('Tipo de combustible')"
                            required
                        />
                        @error('tipo_conbustible')
                            <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="flex justify-end border-t pt-4 gap-3">
                    <flux:button type="button" wire:click="closeModal" variant="ghost">
                        {{ __('Cancelar') }}
                    </flux:button>
                    <flux:button type="submit" variant="primary">
                        {{ __('Añadir') }}
                    </flux:button>
                </div>
            </form>
        </div>
    </flux:modal>
</div>
