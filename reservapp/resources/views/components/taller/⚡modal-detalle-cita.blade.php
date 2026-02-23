<?php

use Livewire\Component;
use App\Models\Cita;
use App\Models\Dia;
use Livewire\Attributes\On;

new class extends Component {
    public $modalAbierto = false;
    public $citaSeleccionada = null;
    public $mostrarNuevaFecha = false;
    public $nuevaFecha = '';

    public function abrirModal($citaId)
    {
        $this->citaSeleccionada = Cita::with('coche.usuario')->find($citaId);
        $this->mostrarNuevaFecha = false;
        $this->nuevaFecha = '';
        $this->modalAbierto = true;
    }

    public function cerrarModal()
    {
        $this->modalAbierto = false;
        $this->citaSeleccionada = null;
        $this->mostrarNuevaFecha = false;
    }

    #[On('abrirModalCita')]
    public function abrirModalDesdeEvento($id)
    {
        $this->abrirModal($id);
    }

    public function aceptar()
    {
        $cita = Cita::find($this->citaSeleccionada->id_cita);
        $cita->estado = Cita::ESTADO_ACEPTADO;
        $cita->save();
        $this->cerrarModal();
        $this->dispatch('citaActualizada');
    }

    public function rechazar()
    {
        $cita = Cita::find($this->citaSeleccionada->id_cita);
        $cita->estado = Cita::ESTADO_RECHAZADO_POR_TALLER;
        $cita->save();
        $this->cerrarModal();
        $this->dispatch('citaActualizada');
    }

    public function proponerFecha()
    {
        $this->validate(
            [
                'nuevaFecha' => 'required|date|after:today',
            ],
            [
                'nuevaFecha.required' => 'Debes seleccionar una fecha.',
                'nuevaFecha.date' => 'La fecha no es válida.',
                'nuevaFecha.after' => 'La fecha debe ser posterior a hoy.',
            ],
        );

        $cita = Cita::find($this->citaSeleccionada->id_cita);

        $dia = Dia::firstOrCreate(['fecha' => $this->nuevaFecha, 'id_taller' => auth()->user()->taller->id_taller], ['estado' => 0]);

        $cita->fecha = $dia->fecha;
        $cita->estado = Cita::ESTADO_FECHA_PROPUESTA;
        $cita->save();

        $this->cerrarModal();
        $this->dispatch('citaActualizada');
    }
};
?>
<div>
    @if ($modalAbierto && $citaSeleccionada)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
            <div class="bg-background text-text rounded-xl shadow-xl w-[520px] p-6 relative">

                <!-- Cerrar -->
                <button wire:click="cerrarModal"
                    class="absolute top-3 right-3 text-text/40 hover:text-accent text-xl font-bold">
                    &times;
                </button>

                <h3 class="text-xl font-bold text-left mb-6">Detalles cita</h3>

                <!-- INFO -->
                <div class="grid grid-cols-2 gap-6 mb-6 text-sm">
                    <div class="space-y-3">
                        <p><span class="font-semibold text-text/70">Usuario:</span>
                            {{ $citaSeleccionada->coche->usuario->nombre }}</p>
                        <p><span class="font-semibold text-text/70">Marca:</span>
                            {{ $citaSeleccionada->coche->marca }}</p>
                    </div>
                    <div class="space-y-3">
                        <p><span class="font-semibold text-text/70">Fecha:</span>
                            {{ $citaSeleccionada->fecha }}</p>
                        <p><span class="font-semibold text-text/70">Modelo:</span>
                            {{ $citaSeleccionada->coche->modelo }}</p>
                    </div>
                </div>

                <!-- MOTIVO -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold mb-2 text-text/70">Detalles de la solicitud</label>
                    <textarea readonly
                        class="w-full h-24 p-3 border border-secondary/20 rounded-lg bg-secondary/5 text-text text-sm resize-none">{{ $citaSeleccionada->motivo }}</textarea>
                </div>

                <!-- BOTONES -->
                <div class="flex gap-3 justify-end mb-4">
                    <button wire:click="rechazar"
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                        Rechazar
                    </button>
                    <button wire:click="$set('mostrarNuevaFecha', !mostrarNuevaFecha)"
                        class="px-4 py-2 bg-secondary text-white rounded-lg hover:opacity-90 transition">
                        Proponer nueva fecha
                    </button>
                    <button wire:click="aceptar"
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                        Aceptar
                    </button>
                </div>

                <!-- NUEVA FECHA -->
                @if ($mostrarNuevaFecha)
                    <div class="border-t border-secondary/20 pt-4 mt-2">
                        <label class="block text-sm font-semibold mb-2 text-text/70">Nueva fecha propuesta</label>
                        <div class="flex gap-3">
                            <input type="date" wire:model="nuevaFecha"
                                class="flex-1 px-3 py-2 border border-text/20 rounded-lg bg-transparent text-text focus:ring-2 focus:ring-primary focus:border-primary">
                            @error('nuevaFecha')
                                <span class="text-accent text-xs">{{ $message }}</span>
                            @enderror
                            <button wire:click="proponerFecha"
                                class="px-6 py-2 bg-secondary text-white rounded-lg hover:opacity-90 transition whitespace-nowrap">
                                Confirmar
                            </button>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    @endif
</div>
