<?php

use Livewire\Component;
use App\Models\Coche;
use App\Models\TipoCombustible;

new class extends Component
{
    public bool $mostrar = false;

    public string $matricula        = '';
    public string $marca            = '';
    public string $modelo           = '';
    public string $n_bastidor       = '';
    public string $tipo_combustible = '';

    public $tiposPropulsion = [];
    public ?int $carId = null;

    protected function rules(): array
    {
        return [
            'matricula'        => 'required|string|max:10',
            'marca'            => 'required|string|max:100',
            'modelo'           => 'required|string|max:100',
            'n_bastidor'       => 'required|string|max:17',
            'tipo_combustible' => 'required|integer|exists:tipo_propulsion,tipo_combustible',
        ];
    }

    protected array $messages = [
        'matricula.required'        => 'La matrícula es obligatoria.',
        'marca.required'            => 'La marca es obligatoria.',
        'modelo.required'           => 'El modelo es obligatorio.',
        'n_bastidor.required'       => 'El número de bastidor es obligatorio.',
        'tipo_combustible.required' => 'El tipo de propulsión es obligatorio.',
        'tipo_combustible.exists'   => 'El tipo de propulsión seleccionado no es válido.',
    ];

    public function mount(Coche $coche): void
    {
        $this->carId           = $coche->id_coche;
        $this->matricula       = $coche->matricula;
        $this->marca           = $coche->marca;
        $this->modelo          = $coche->modelo;
        $this->n_bastidor      = $coche->n_bastidor;
        $this->tipo_combustible = $coche->tipo_combustible;
        $this->tiposPropulsion = TipoCombustible::all();
    }

    public function abrirModal(): void
    {
        $this->mostrar = true;
    }

    public function cerrarModal(): void
    {
        $this->mostrar = false;
        $this->resetValidation();
    }

    public function actualizar(): void
    {
        $this->validate();

        Coche::where('id_coche', $this->carId)->update([
            'matricula'        => strtoupper($this->matricula),
            'marca'            => $this->marca,
            'modelo'           => $this->modelo,
            'n_bastidor'       => strtoupper($this->n_bastidor),
            'tipo_combustible' => $this->tipo_combustible,
        ]);

        $this->cerrarModal();
        $this->dispatch('cocheActualizado');
    }
};
?>

<div>
    <flux:button wire:click="abrirModal" variant='primary'>
        Editar Coche
    </flux:button>

    @if($mostrar)
        <div
            class="fixed inset-0 z-40 bg-black/50 backdrop-blur-sm dark:bg-black/70"
            wire:click="cerrarModal"
        ></div>

        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="w-full max-w-lg rounded-xl shadow-2xl
                        bg-white text-gray-900
                        dark:bg-gray-900 dark:text-gray-100">

                <div class="p-6 space-y-6">

                    {{-- Header --}}
                    <div class="flex items-start justify-between border-b pb-4
                                border-gray-200 dark:border-gray-700">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                Editar Coche
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Modifica los datos del vehículo.
                            </p>
                        </div>
                        <button
                            type="button"
                            wire:click="cerrarModal"
                            class="rounded-lg p-1.5 transition
                                text-gray-400 hover:bg-gray-100 hover:text-gray-600
                                dark:text-gray-500 dark:hover:bg-gray-800 dark:hover:text-gray-300"
                        >
                            <flux:icon.x-mark class="size-5" />
                        </button>
                    </div>

                    {{-- Form --}}
                    <form wire:submit="actualizar" class="space-y-4">

                        {{-- Matrícula --}}
                        <div class="relative z-0 w-full group">
                            {{-- Matrícula --}}
                            <input wire:model="matricula" type="text" id="matricula" placeholder=" "
                                class="block py-2.5 px-0 w-full text-sm bg-transparent border-0 border-b-2 appearance-none focus:outline-none focus:ring-0 peer uppercase
                                    text-gray-900 border-gray-300 focus:border-blue-600
                                    dark:text-gray-100 dark:border-gray-600 dark:focus:border-blue-400" />
                            <label for="matricula"
                                class="peer-focus:font-medium absolute text-sm duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6
                                    text-gray-500 peer-focus:text-blue-600
                                    dark:text-gray-400 dark:peer-focus:text-blue-400">
                                Matrícula
                            </label>
                            @error('matricula') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>

                        {{-- Marca y Modelo --}}
                        <div class="grid grid-cols-2 gap-4">
                            <div class="relative z-0 w-full group">
                                <input wire:model="marca" type="text" id="marca" placeholder=" "
                                    class="block py-2.5 px-0 w-full text-sm bg-transparent border-0 border-b-2 appearance-none focus:outline-none focus:ring-0 peer
                                        text-gray-900 border-gray-300 focus:border-blue-600
                                        dark:text-gray-100 dark:border-gray-600 dark:focus:border-blue-400" />
                                <label for="marca"
                                    class="peer-focus:font-medium absolute text-sm duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6
                                        text-gray-500 peer-focus:text-blue-600
                                        dark:text-gray-400 dark:peer-focus:text-blue-400">
                                    Marca
                                </label>
                                @error('marca') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>
                            <div class="relative z-0 w-full group">
                                <input wire:model="modelo" type="text" id="modelo" placeholder=" "
                                    class="block py-2.5 px-0 w-full text-sm bg-transparent border-0 border-b-2 appearance-none focus:outline-none focus:ring-0 peer
                                        text-gray-900 border-gray-300 focus:border-blue-600
                                        dark:text-gray-100 dark:border-gray-600 dark:focus:border-blue-400" />
                                <label for="modelo"
                                    class="peer-focus:font-medium absolute text-sm duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6
                                        text-gray-500 peer-focus:text-blue-600
                                        dark:text-gray-400 dark:peer-focus:text-blue-400">
                                    Modelo
                                </label>
                                @error('modelo') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        {{-- Número de bastidor --}}
                        <div class="relative z-0 w-full group">
                            <input wire:model="n_bastidor" type="text" id="n_bastidor" placeholder=" "
                                class="block py-2.5 px-0 w-full text-sm bg-transparent border-0 border-b-2 appearance-none focus:outline-none focus:ring-0 peer uppercase
                                    text-gray-900 border-gray-300 focus:border-blue-600
                                    dark:text-gray-100 dark:border-gray-600 dark:focus:border-blue-400" />
                            <label for="n_bastidor"
                                class="peer-focus:font-medium absolute text-sm duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6
                                    text-gray-500 peer-focus:text-blue-600
                                    dark:text-gray-400 dark:peer-focus:text-blue-400">
                                Número de bastidor
                            </label>
                            @error('n_bastidor') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>

                        {{-- Tipo de propulsión --}}
                        <div class="relative z-0 w-full group">
                            <label for="tipo_combustible"
                                class="block text-sm mb-1
                                    text-gray-500 dark:text-gray-400">
                                Tipo de propulsión
                            </label>
                            <select wire:model="tipo_combustible" id="tipo_combustible"
                                class="block py-2.5 px-0 w-full text-sm bg-transparent border-0 border-b-2 appearance-none focus:outline-none focus:ring-0
                                    text-gray-900 border-gray-300 focus:border-blue-600
                                    dark:text-gray-100 dark:border-gray-600 dark:focus:border-blue-400">
                                <option value="" disabled>Selecciona el tipo de propulsión</option>
                                @foreach($tiposPropulsion as $tipo)
                                    <option value="{{ $tipo->tipo_combustible }}">{{ $tipo->nombre }}</option>
                                @endforeach
                            </select>
                            @error('tipo_combustible') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>

                        {{-- Botones --}}
                        <div class="flex justify-end gap-3 pt-4 border-t
                                    border-gray-200 dark:border-gray-700">
                            <flux:button type="button" wire:click="cerrarModal" variant="ghost">
                                Cancelar
                            </flux:button>
                            <flux:button type="submit" variant="primary">
                                <span wire:loading.remove wire:target="actualizar">Guardar cambios</span>
                                <span wire:loading wire:target="actualizar">Guardando…</span>
                            </flux:button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    @endif
</div>