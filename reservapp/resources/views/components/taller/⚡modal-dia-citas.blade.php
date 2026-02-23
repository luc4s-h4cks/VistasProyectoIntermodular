<?php

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Cita;

new class extends Component {
    public $modalAbierto = false;
    public $fechaSeleccionada = '';
    public $citas = [];
    public $permitirCitas = 'si';

    #[On('abrirModalDia')]
    public function abrirModal($fecha)
    {
        $this->fechaSeleccionada = $fecha;

        $this->citas = Cita::whereDate('fecha', $fecha)
            ->whereIn('estado', [Cita::ESTADO_ACEPTADO, Cita::ESTADO_FECHA_ACEPTADA_CLIENTE])
            ->with(['coche.usuario'])
            ->get();

        $this->modalAbierto = true;
    }

    public function cerrarModal()
    {
        $this->modalAbierto = false;
        $this->fechaSeleccionada = '';
        $this->citas = [];
    }

    public function cancelarCita($citaId)
    {
        $cita = Cita::find($citaId);
        $cita->estado = Cita::ESTADO_RECHAZADO_POR_TALLER;
        $cita->save();
        $this->abrirModal($this->fechaSeleccionada);
    }

    

};
?>

<div>
    @if ($modalAbierto)
        <div class="fixed inset-0 bg-black bg-opacity-20 flex items-center justify-center z-50">
            <div class="bg-white rounded-xl shadow-xl w-[600px] max-h-[90vh] flex flex-col p-6 relative">

                <!-- Cerrar -->
                <button wire:click="cerrarModal"
                    class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 text-2xl font-bold">
                    &times;
                </button>

                <!-- Título -->
                <h2 class="text-2xl font-bold mb-6 text-center">
                    DIA {{ $fechaSeleccionada }}
                </h2>

                <!-- Select y cantidad -->
                <div class="flex items-center justify-between mb-4 pb-4 border-b">
                    <select wire:model="permitirCitas"
                        class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="si">Permitir más citas</option>
                        <option value="no">No permitir más citas</option>
                    </select>
                    <div class="text-sm font-medium text-gray-700">
                        Citas aceptadas:
                        <span class="font-bold text-blue-600">{{ count($citas) }}</span>
                    </div>
                </div>

                <!-- Citas -->
                <div class="flex-1 overflow-y-auto space-y-4 pr-2" style="max-height: 500px;">
                    @forelse ($citas as $cita)
                        <div class="border-2 border-gray-300 rounded-lg p-4">
                            <div class="flex gap-4">
                                <div class="flex-shrink-0">
                                    <div
                                        class="w-24 h-24 bg-gray-300 rounded-full flex items-center justify-center text-gray-500">
                                        img
                                    </div>
                                </div>
                                <div class="flex-1 grid grid-cols-2 gap-4">
                                    <div class="space-y-1">
                                        <p class="text-sm"><span class="font-semibold">Usuario:</span>
                                            {{ $cita->coche->usuario->nombre }}</p>
                                        <p class="text-sm"><span class="font-semibold">Marca:</span>
                                            {{ $cita->coche->marca }}</p>
                                        <p class="text-sm"><span class="font-semibold">Modelo:</span>
                                            {{ $cita->coche->modelo }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold mb-1">Motivo</p>
                                        <div class="text-xs bg-gray-100 p-2 rounded h-16 overflow-y-auto">
                                            {{ $cita->motivo }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex gap-2 mt-3">
                                <a href="{{ route('cita.factura', $cita->id_cita) }}"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm">
                                    Marcar como terminado
                                </a>
                                <button wire:click="cancelarCita({{ $cita->id_cita }})"
                                    wire:confirm="¿Seguro que quieres cancelar esta cita?"
                                    class="px-4 py-2 text-sm bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                                    Cancelar
                                </button>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-gray-500 mt-4">No hay citas para este día</p>
                    @endforelse
                </div>

            </div>
        </div>
    @endif
</div>
