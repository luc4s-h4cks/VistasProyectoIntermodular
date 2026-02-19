<?php

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Taller;

new class extends Component
{
    public $talleres = [];
    public $search = '';
    public $filtroServicios = [];
    public $filtroVehiculos = [];

    public function mount()
    {
        $this->talleres = Taller::all();
    }

    public function updatedSearch()
    {
        $this->aplicarFiltros();
    }

    #[On('filtros-actualizados')]
    public function recibirFiltros(array $servicios, array $vehiculos)
    {
        $this->filtroServicios = $servicios;
        $this->filtroVehiculos = $vehiculos;
        $this->aplicarFiltros();
    }

    private function aplicarFiltros()
    {
        $query = Taller::query();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('descripcion', 'like', "%{$this->search}%")
                    ->orWhere('tipo_vehiculo', 'like', "%{$this->search}%")
                    ->orWhere('tipo_servicio', 'like', "%{$this->search}%")
                    ->orWhere('nombre', 'like', "%{$this->search}%")
                    ->orWhere('handle', 'like', "%{$this->search}%");
            });
        }

        if (!empty($this->filtroVehiculos)) {
            $query->where(function ($q) {
                foreach ($this->filtroVehiculos as $vehiculo) {
                    $q->orWhere('tipo_vehiculo', 'like', "%\"{$vehiculo}\"%");
                }
            });
        }

        if (!empty($this->filtroServicios)) {
            $query->where(function ($q) {
                foreach ($this->filtroServicios as $servicio) {
                    $q->orWhere('tipo_servicio', 'like', "%\"{$servicio}\"%");
                }
            });
        }

        $this->talleres = $query->get();
    }

};
?>

<div class="w-3/5 mx-auto">
    <h1> Buscador </h1>
    <br/>
    <flux:input icon="magnifying-glass" placeholder="Buscar talleres..." clearable
    wire:model.live="search"
    />

    <div class="mt-6 flex gap-6">

        {{-- Filtros --}}
        <div class="w-52 flex-shrink-0">
            <livewire:filtros/>
        </div>

        {{-- Lista de talleres --}}
        <div class="flex-1 flex flex-col gap-4">
            @foreach ($talleres as $taller)
                <div class="w-full flex gap-4 bg-background border-l-4 border-primary rounded-xl shadow-sm p-4">

                    {{-- Imagen --}}
                    <div class="flex-shrink-0">
                        @if ($taller->img_perfil)
                            <img src="{{ asset('storage/imgTalleres/' . $taller->img_perfil) }}"
                                 alt="{{ $taller->nombre }}"
                                 class="w-24 h-24 object-cover rounded-lg border-2 border-secondary">
                        @else
                            <div class="w-24 h-24 bg-zinc-200 rounded-lg flex items-center justify-center text-zinc-400 text-xs text-center">
                                Sin imagen
                            </div>
                        @endif
                    </div>

                    {{-- Info --}}
                    <div class="flex flex-col justify-center gap-1">
                        <h2 class="text-lg font-bold text-primary">{{ $taller->nombre }} <span class="text-sm text-gray-500">{{ $taller->handle}}</span></h2>
                        <p class="text-sm text-text">{{ $taller->descripcion }}</p>
                        <p class="text-sm text-zinc-500">
                            <span class="font-semibold text-accent">Vehículos admitidos:</span>
                            @foreach ($taller->tipo_vehiculo as $vehiculo)
                                 {{ $vehiculo }}@if (!$loop->last), @else. @endif
                            @endforeach
                        </p>
                        <p class="text-sm text-zinc-500">
                            <span class="font-semibold text-accent">Servicios:</span>
                            @foreach ($taller->tipo_servicio as $servicio)
                                {{ $servicio }}@if (!$loop->last), @else. @endif
                            @endforeach
                        </p>
                    </div>

                </div>
            @endforeach
        </div>

    </div>
</div>
