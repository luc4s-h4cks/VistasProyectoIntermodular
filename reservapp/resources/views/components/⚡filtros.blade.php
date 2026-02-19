<?php

use Livewire\Component;

new class extends Component
{
    public $filtroServicios = [];
    public $filtroVehiculos = [];

    public function updatedFiltroServicios()
    {
        $this->dispatch('filtros-actualizados', servicios: $this->filtroServicios, vehiculos: $this->filtroVehiculos);
    }

    public function updatedFiltroVehiculos()
    {
        $this->dispatch('filtros-actualizados', servicios: $this->filtroServicios, vehiculos: $this->filtroVehiculos);
    }
};
?>

<div class="space-y-6">

    <div>
        <h3 class="text-sm font-semibold text-text mb-2">Tipo de vehículo</h3>
        <div class="space-y-1">
            @foreach (['Coches', 'Motos', 'Camiones'] as $tipo)
                <label class="flex items-center gap-2 text-sm text-zinc-600 cursor-pointer">
                    <input type="checkbox" wire:model.live="filtroVehiculos" value="{{ $tipo }}"
                        class="w-4 h-4 rounded border-zinc-300 text-primary focus:ring-primary">
                    {{ $tipo }}
                </label>
            @endforeach
        </div>
    </div>

    <div>
        <h3 class="text-sm font-semibold text-text mb-2">Servicios</h3>
        <div class="space-y-1">
            @foreach (['Frenos y suspensión', 'Mantenimiento', 'Diagnosis', 'Reparación de motor', 'Electricidad automotriz'] as $servicio)
                <label class="flex items-center gap-2 text-sm text-zinc-600 cursor-pointer">
                    <input type="checkbox" wire:model.live="filtroServicios" value="{{ $servicio }}"
                        class="w-4 h-4 rounded border-zinc-300 text-primary focus:ring-primary">
                    {{ $servicio }}
                </label>
            @endforeach
        </div>
    </div>

</div>
