<?php

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Cita;
use App\Models\Dia;

new class extends Component {
    public $modalAbierto = false;
    public $fechaSeleccionada = '';
    public $citas = [];
    public $estadoDia = Dia::ESTADO_LIBRE;

    #[On('abrir-modal-dia')]
    public function abrirModal($fecha)
    {
        $this->fechaSeleccionada = $fecha;

        $this->citas = Cita::whereDate('fecha', $fecha)
            ->whereIn('estado', [Cita::ESTADO_ACEPTADO, Cita::ESTADO_FECHA_ACEPTADA_CLIENTE])
            ->with(['coche.usuario'])
            ->get();

        // Cargamos el estado actual del día si existe
        $dia = Dia::where('fecha', $fecha)
            ->where('id_taller', auth()->user()->taller->id_taller)
            ->first();

        $this->estadoDia = $dia ? $dia->estado : Dia::ESTADO_LIBRE;

        $this->modalAbierto = true;
    }

    public function updatedEstadoDia($valor)
    {
        Dia::updateOrCreate(
            [
                'fecha' => $this->fechaSeleccionada,
                'id_taller' => auth()->user()->taller->id_taller,
            ],
            ['estado' => $valor],
        );
    }

    public function cerrarModal()
    {
        $this->modalAbierto = false;
        $this->fechaSeleccionada = '';
        $this->citas = [];
        $this->reset(['modalAbierto', 'fechaSeleccionada', 'citas']);
    }

    public function cancelarCita($citaId)
    {
        $cita = Cita::find($citaId);
        $cita->estado = Cita::ESTADO_RECHAZADO_POR_TALLER;
        $cita->save();
        $this->abrirModal($this->fechaSeleccionada);
    }

    public function mount() {}
};
?>

<div x-init="window.addEventListener('abrir-modal-dia', (e) => {
    $wire.abrirModal(e.detail.fecha)
})">
    @if ($modalAbierto)
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
            <div class="bg-background text-text rounded-xl shadow-xl w-[600px] max-h-[90vh] flex flex-col p-6 relative">

                <button wire:click="cerrarModal"
                    class="absolute top-3 right-3 text-text/40 hover:text-accent text-2xl font-bold">
                    &times;
                </button>

                <h2 class="text-2xl font-bold mb-6 text-center">
                    DIA {{ $fechaSeleccionada }}
                </h2>

                <div class="flex items-center justify-between mb-4 pb-4 border-b border-secondary/20">
                    <select wire:model.live="estadoDia"
                        class="px-4 py-2 border border-text/20 rounded-lg bg-transparent text-text focus:ring-2 focus:ring-primary focus:border-primary">
                        <option value="{{ \App\Models\Dia::ESTADO_LIBRE }}">Permitir más citas</option>
                        <option value="{{ \App\Models\Dia::ESTADO_OCUPADO }}">No permitir más citas</option>
                    </select>
                    <div class="text-sm font-medium text-text/70">
                        Citas aceptadas:
                        <span class="font-bold text-primary">{{ count($citas) }}</span>
                    </div>
                </div>

                <div class="flex-1 overflow-y-auto space-y-4 pr-2" style="max-height: 500px;">
                    @forelse ($citas as $cita)
                        <div class="border-2 border-secondary/20 rounded-lg p-4">
                            <div class="flex gap-4">
                                <div class="flex-shrink-0">
                                    <div class="w-24 h-24 bg-secondary/10 rounded-full flex items-center justify-center text-text/40">
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
                                        <div class="text-xs bg-secondary/5 border border-secondary/20 text-text/70 p-2 rounded h-16 overflow-y-auto">
                                            {{ $cita->motivo }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex gap-2 mt-3">
                                <a href="{{ route('cita.factura', $cita->id_cita) }}"
                                    class="px-4 py-2 bg-primary text-white rounded-lg hover:opacity-90 transition text-sm">
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
                        <p class="text-center text-text/40 mt-4">No hay citas para este día</p>
                    @endforelse
                </div>

            </div>
        </div>
    @endif
</div>
