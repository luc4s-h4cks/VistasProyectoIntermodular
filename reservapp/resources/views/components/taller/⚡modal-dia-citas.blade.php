<?php

use Livewire\Component;
use App\Models\Cita;
use Illuminate\Support\Facades\Auth;

new class extends Component {
    public $abierto = false;
    public $fechaSeleccionada = null;
    public $citas = [];
    public $permitirCitas = 'si';

    protected $listeners = [
        'abrirModalDia' => 'abrirModalDia',
    ];

    public function abrirModalDia($data)
    {
        $fecha = $data['fecha'];

        $this->fechaSeleccionada = $fecha;
        $this->abierto = true;

        $taller = Auth::user()->taller;

        $this->citas = Cita::with('coche.usuario')->where('id_taller', $taller->id_taller)->where('fecha', $fecha)->where('estado', Cita::ESTADO_ACEPTADO)->get();
    }

    public function cerrar()
    {
        $this->reset(['abierto', 'fechaSeleccionada', 'citas']);
    }

    public function marcarTerminado($id)
    {
        $cita = Cita::find($id);
        $cita->estado = Cita::ESTADO_TEMINADO;
        $cita->save();

        $this->abrirModalDia($this->fechaSeleccionada);
    }

    public function cancelar($id) {}

    public function render()
    {
        return view('components.taller.⚡modal-dia-citas');
    }
};
?>

<div>
    @if ($abierto)
        <div class="fixed inset-0 bg-black bg-opacity-20 flex items-center justify-center z-50">

            <div class="bg-white rounded-xl shadow-xl w-[600px] max-h-[90vh] flex flex-col p-6 relative">

                {{-- Botón cerrar --}}
                <button wire:click="cerrar"
                    class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 text-2xl font-bold">
                    &times;
                </button>

                <h2 class="text-2xl font-bold mb-6 text-center">
                    Día {{ $fechaSeleccionada }}
                </h2>

                {{-- Select permitir citas --}}
                <div class="flex items-center justify-between mb-4 pb-4 border-b">
                    <select wire:model="permitirCitas"
                        class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="si">Permitir más citas</option>
                        <option value="no">No permitir más citas</option>
                    </select>

                    <div class="text-sm font-medium text-gray-700">
                        Cantidad de citas aceptadas:
                        <span class="font-bold text-blue-600">
                            {{ count($citas) }}
                        </span>
                    </div>
                </div>

                <div class="flex-1 overflow-y-auto space-y-4 pr-2">

                    @forelse($citas as $cita)
                        <div class="border-2 border-gray-300 rounded-lg p-4">

                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <p><strong>Usuario:</strong> {{ $cita->coche->usuario->nombre }}</p>
                                    <p><strong>Marca:</strong> {{ $cita->coche->marca }}</p>
                                    <p><strong>Modelo:</strong> {{ $cita->coche->modelo }}</p>
                                </div>

                                <div>
                                    <p class="font-semibold mb-1">Motivo</p>
                                    <div class="text-xs bg-gray-100 p-2 rounded h-16 overflow-y-auto">
                                        {{ $cita->motivo }}
                                    </div>
                                </div>
                            </div>

                            <div class="flex gap-2 mt-3">
                                <button wire:click="marcarTerminado({{ $cita->id_cita }})"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                    Marcar como terminado
                                </button>

                                <button wire:click="cancelar({{ $cita->id_cita }})"
                                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                                    Cancelar
                                </button>
                            </div>

                        </div>
                    @empty
                        <p class="text-center text-gray-500 mt-4">
                            No hay citas para este día por ahora
                        </p>
                    @endforelse

                </div>

            </div>
        </div>
    @endif
</div>
