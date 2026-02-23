<?php

use Livewire\Component;
use App\Models\Coche;
use App\Models\TipoCombustible;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;

new class extends Component
{
    use WithFileUploads;

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
            'matricula'        => 'required|string|max:10|unique:coche,matricula',
            'marca'            => 'required|string|max:100',
            'modelo'           => 'required|string|max:100',
            'n_bastidor'       => 'required|string|max:17|unique:coche,n_bastidor',
            'tipo_combustible' => 'required|integer|exists:tipo_propulsion,tipo_combustible',
        ];
    }

    protected array $messages = [
        'matricula.required'        => 'La matrícula es obligatoria.',
        'matricula.unique'          => 'Esta matrícula ya está registrada.',
        'marca.required'            => 'La marca es obligatoria.',
        'modelo.required'           => 'El modelo es obligatorio.',
        'n_bastidor.required'       => 'El número de bastidor es obligatorio.',
        'n_bastidor.unique'         => 'Este número de bastidor ya está registrado.',
        'tipo_combustible.required' => 'El tipo de propulsión es obligatorio.',
        'tipo_combustible.exists'   => 'El tipo de propulsión seleccionado no es válido.',
    ];

    public function mount(?int $carId = null): void{
        $this->carId = $carId;
        $this->tiposPropulsion = TipoCombustible::all();
    }

    public function abrirModal(): void
    {
        $this->limpiarForm();
        $this->mostrar = true;
    }

    public function cerrarModal(): void
    {
        $this->mostrar = false;
        $this->limpiarForm();
    }

    public function guardar(): void
    {
        $this->validate();

        Coche::create([
            'matricula'        => strtoupper($this->matricula),
            'marca'            => $this->marca,
            'modelo'           => $this->modelo,
            'n_bastidor'       => strtoupper($this->n_bastidor),
            'tipo_combustible' => $this->tipo_combustible,
            'id_usuario'       => auth()->id(),
        ]);

        $this->cerrarModal();
        $this->dispatch('cocheCreado');
    }

    private function limpiarForm(): void
    {
        $this->reset(['matricula', 'marca', 'modelo', 'n_bastidor', 'tipo_combustible']);
        $this->resetValidation();
    }
};
?>

<div>
    <flux:button wire:click="abrirModal" variant="primary" icon="plus">
        Nuevo Coche
    </flux:button>

    @if($mostrar)
        <div class="fixed inset-0 z-50 flex items-center justify-center">

            {{-- Backdrop --}}
            <div
                class="absolute inset-0 bg-black/50 backdrop-blur-sm"
                wire:click="cerrarModal"
            ></div>

            {{-- Modal --}}
            <div class="relative z-10 w-full max-w-lg rounded-xl p-6 shadow-2xl
                        bg-background text-text
                        dark:text-[#f5f5f5]">

                {{-- Header --}}
                <div class="mb-6 flex items-start justify-between border-b pb-4
                            border-secondary/30 dark:border-secondary/20">
                    <div>
                        <h3 class="text-lg font-semibold text-primary dark:text-[#1a73a8]">
                            Nuevo Coche
                        </h3>
                        <p class="text-sm text-text/60 dark:text-[#f5f5f5]/60">
                            Rellena los datos del vehículo.
                        </p>
                    </div>
                    <button
                        wire:click="cerrarModal"
                        class="rounded-lg p-1.5 transition
                            text-text/40 hover:bg-secondary/10 hover:text-accent
                            dark:text-[#f5f5f5]/40 dark:hover:bg-secondary/10 dark:hover:text-accent"
                    >
                        <flux:icon.x-mark class="size-5" />
                    </button>
                </div>

                {{-- Form --}}
                <form wire:submit="guardar" class="space-y-4">

                    {{-- Matrícula --}}
                    <div class="relative z-0 w-full group">
                        <input wire:model="matricula" type="text" id="matricula" placeholder=" "
                            class="block py-2.5 px-0 w-full text-sm bg-transparent border-0 border-b-2 appearance-none focus:outline-none focus:ring-0 peer uppercase
                                    text-text border-text/20 focus:border-primary
                                    dark:text-[#f5f5f5] dark:border-[#f5f5f5]/20 dark:focus:border-primary"
                        />
                        <label for="matricula"
                            class="peer-focus:font-medium absolute text-sm text-text/50 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0]
                                   peer-focus:text-primary
                                   peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0
                                   peer-focus:scale-75 peer-focus:-translate-y-6
                                   dark:text-[#f5f5f5]/50 dark:peer-focus:text-primary">
                            Matrícula
                        </label>
                        @error('matricula') <p class="mt-1 text-xs text-accent">{{ $message }}</p> @enderror
                    </div>

                    {{-- Marca y Modelo --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div class="relative z-0 w-full group">
                            <input wire:model="marca" type="text" id="marca" placeholder=" "
                                class="block py-2.5 px-0 w-full text-sm bg-transparent border-0 border-b-2 appearance-none focus:outline-none focus:ring-0 peer uppercase
                                    text-text border-text/20 focus:border-primary
                                    dark:text-[#f5f5f5] dark:border-[#f5f5f5]/20 dark:focus:border-primary"
                            />
                            <label for="marca"
                                class="peer-focus:font-medium absolute text-sm text-text/50 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0]
                                       peer-focus:text-primary
                                       peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0
                                       peer-focus:scale-75 peer-focus:-translate-y-6
                                       dark:text-[#f5f5f5]/50 dark:peer-focus:text-primary">
                                Marca
                            </label>
                            @error('marca') <p class="mt-1 text-xs text-accent">{{ $message }}</p> @enderror
                        </div>
                        <div class="relative z-0 w-full group">
                            <input wire:model="modelo" type="text" id="modelo" placeholder=" "
                                class="block py-2.5 px-0 w-full text-sm bg-transparent border-0 border-b-2 appearance-none focus:outline-none focus:ring-0 peer
                                        text-text border-text/20 focus:border-primary
                                        dark:text-[#f5f5f5] dark:border-[#f5f5f5]/20 dark:focus:border-primary"
                            />
                            <label for="modelo"
                                class="peer-focus:font-medium absolute text-sm text-text/50 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0]
                                       peer-focus:text-primary
                                       peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0
                                       peer-focus:scale-75 peer-focus:-translate-y-6
                                       dark:text-[#f5f5f5]/50 dark:peer-focus:text-primary">
                                Modelo
                            </label>
                            @error('modelo') <p class="mt-1 text-xs text-accent">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Número de bastidor --}}
                    <div class="relative z-0 w-full group">
                        <input wire:model="n_bastidor" type="text" id="n_bastidor" placeholder=" "
                            class="block py-2.5 px-0 w-full text-sm bg-transparent border-0 border-b-2 appearance-none focus:outline-none focus:ring-0 peer uppercase
                                    text-text border-text/20 focus:border-primary
                                    dark:text-[#f5f5f5] dark:border-[#f5f5f5]/20 dark:focus:border-primary"
                        />
                        <label for="n_bastidor"
                            class="peer-focus:font-medium absolute text-sm text-text/50 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0]
                                   peer-focus:text-primary
                                   peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0
                                   peer-focus:scale-75 peer-focus:-translate-y-6
                                   dark:text-[#f5f5f5]/50 dark:peer-focus:text-primary">
                            Número de bastidor
                        </label>
                        @error('n_bastidor') <p class="mt-1 text-xs text-accent">{{ $message }}</p> @enderror
                    </div>

                    {{-- Tipo de propulsión --}}
                    <div class="relative z-0 w-full group">
                        <label for="tipo_combustible" class="block text-sm text-text/50 mb-1 dark:text-[#f5f5f5]/50">
                            Tipo de propulsión
                        </label>
                        <select wire:model="tipo_combustible" id="tipo_combustible"
                            class="block py-2.5 px-0 w-full text-sm bg-transparent border-0 border-b-2 appearance-none focus:outline-none focus:ring-0
                                    text-text border-text/20 focus:border-primary
                                    dark:text-[#f5f5f5] dark:border-[#f5f5f5]/20 dark:focus:border-primary">
                            <option value="" disabled>Selecciona el tipo de propulsión</option>
                            @foreach($tiposPropulsion as $tipo)
                                <option value="{{ $tipo->tipo_combustible }}">{{ $tipo->nombre }}</option>
                            @endforeach
                        </select>
                        @error('tipo_combustible') <p class="mt-1 text-xs text-accent">{{ $message }}</p> @enderror
                    </div>

                    {{-- Botones --}}
                    <div class="flex justify-end gap-3 pt-4 border-t border-secondary/20">
                        <flux:button type="button" wire:click="cerrarModal" variant="ghost">
                            Cancelar
                        </flux:button>
                        <flux:button type="submit" variant="primary">
                            <span wire:loading.remove wire:target="guardar">Crear coche</span>
                            <span wire:loading wire:target="guardar">Guardando…</span>
                        </flux:button>
                    </div>

                </form>
            </div>
        </div>
    @endif
</div>
