<?php

use Livewire\Component;
use App\Models\Cita;

new class extends Component {
    public string $tab = 'solicitudes';
    public bool $abiertoDetalle = false;
    public ?int $citaId = null;
    public bool $mostrarNuevaFecha = false;
    public string $nuevaFecha = '';
    public string $tramoHorario = 'manana';

    public function abrirDetalle(int $id): void
    {
        $this->citaId = $id;
        $this->abiertoDetalle = true;
        $this->mostrarNuevaFecha = false;
        $this->nuevaFecha = '';
    }

    public function cerrarDetalle(): void
    {
        $this->abiertoDetalle = false;
        $this->citaId = null;
        $this->mostrarNuevaFecha = false;
    }

    public function aceptarCita(): void
    {
        $cita = Cita::findOrFail($this->citaId);
        $cita->estado = Cita::ESTADO_ACEPTADO;
        $cita->save();
        $this->cerrarDetalle();
        $this->dispatch('calendario-actualizado');
    }

    public function rechazarCita(): void
    {
        $cita = Cita::findOrFail($this->citaId);
        $cita->estado = Cita::ESTADO_RECHAZADO_POR_TALLER;
        $cita->save();
        $this->cerrarDetalle();
        $this->dispatch('calendario-actualizado');
    }

    public function proponerFecha(): void
    {
        $this->validate([
            'nuevaFecha' => 'required|date',
            'tramoHorario' => 'required|in:manana,tarde',
        ]);

        $cita = Cita::findOrFail($this->citaId);
        $cita->fecha = $this->nuevaFecha;
        $cita->tramo_horario = $this->tramoHorario;
        $cita->estado = Cita::ESTADO_FECHA_PROPUESTA;
        $cita->save();

        $this->cerrarDetalle();
        $this->dispatch('calendario-actualizado');
    }

    public function terminarNotificacion(int $id): void
    {
        $cita = Cita::findOrFail($id);
        $cita->estado = Cita::ESTADO_TEMINADO;
        $cita->save();
        $this->dispatch('calendario-actualizado');
    }

    public function aceptarFechaPropuesta(int $id): void
    {
        $cita = Cita::findOrFail($id);
        $cita->estado = Cita::ESTADO_ACEPTADO;
        $cita->save();
        $this->dispatch('calendario-actualizado');
    }

    public function with(): array
    {
        $taller = auth()->user()->taller;

        $citas = Cita::where('id_taller', $taller->id_taller)
            ->with(['coche.usuario'])
            ->get();

        return [
            'solicitudes' => $citas->where('estado', Cita::ESTADO_SOLICITADO),
            'notificaciones' => $citas->filter(fn($c) => in_array($c->estado, [Cita::ESTADO_RECHAZADO_POR_CLIENTE, Cita::ESTADO_PAGADA, Cita::ESTADO_FECHA_ACEPTADA_CLIENTE])),
            'pagos' => $citas->where('estado', Cita::ESTADO_ESPARA_PAGO_TALLER),
            'citaDetalle' => $this->citaId ? $citas->firstWhere('id_cita', $this->citaId) : null,
        ];
    }
};

?>

<div>
    <div class="bg-white shadow-lg rounded-lg p-6">

        {{-- Botones Tabs --}}
        <div class="flex border-b border-gray-200 mb-4">
            <button wire:click="$set('tab', 'solicitudes')"
                class="px-4 py-2 font-medium focus:outline-none {{ $tab === 'solicitudes' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-500' }}">
                Solicitudes de Citas
            </button>

            <button wire:click="$set('tab', 'notificaciones')"
                class="px-4 py-2 font-medium focus:outline-none relative {{ $tab === 'notificaciones' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-500' }}">
                Notificaciones
                @if ($notificaciones->count() > 0)
                    <span
                        class="absolute -top-1 -right-2 bg-red-600 text-white text-xs w-5 h-5 flex items-center justify-center rounded-full">
                        {{ $notificaciones->count() }}
                    </span>
                @endif
            </button>

            <button wire:click="$set('tab', 'pagos')"
                class="px-4 py-2 font-medium focus:outline-none relative {{ $tab === 'pagos' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-500' }}">
                Pagos en Taller
                @if ($pagos->count() > 0)
                    <span
                        class="absolute -top-1 -right-2 bg-purple-600 text-white text-xs w-5 h-5 flex items-center justify-center rounded-full">
                        {{ $pagos->count() }}
                    </span>
                @endif
            </button>
        </div>

        {{-- SOLICITUDES --}}
        @if ($tab === 'solicitudes')
            @if ($solicitudes->isEmpty())
                <p class="text-gray-500 text-center py-6">No hay solicitudes pendientes.</p>
            @else
                <div class="flex flex-wrap gap-4 pt-2">
                    @foreach ($solicitudes as $cita)
                        <div
                            class="bg-white border-2 border-gray-200 rounded-lg shadow-sm p-4 w-56 flex flex-col justify-between hover:border-blue-300 hover:shadow-md transition-all">
                            <div class="space-y-1 mb-3">
                                <p class="text-sm font-semibold text-gray-900 truncate">
                                    {{ $cita->coche->marca }} {{ $cita->coche->modelo }}
                                </p>
                                <p class="text-xs text-gray-500">👤 {{ $cita->coche->usuario->nombre }}</p>
                                <p class="text-xs text-gray-500">
                                    📅 {{ \Carbon\Carbon::parse($cita->fecha)->locale('es')->isoFormat('D MMM YYYY') }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    🕐 {{ $cita->tramo_horario === 'manana' ? 'Mañana' : 'Tarde' }}
                                </p>
                            </div>
                            <button wire:click="abrirDetalle({{ $cita->id_cita }})"
                                class="w-full px-3 py-1.5 text-xs font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition">
                                Ver detalles
                            </button>
                        </div>
                    @endforeach
                </div>
            @endif
        @endif

        {{-- NOTIFICACIONES --}}
        @if ($tab === 'notificaciones')
            @if ($notificaciones->isEmpty())
                <p class="text-gray-600 text-center py-6">No hay notificaciones por ahora.</p>
            @else
                <div class="space-y-4">
                    @foreach ($notificaciones as $cita)
                        @php
                            match ($cita->estado) {
                                Cita::ESTADO_RECHAZADO_POR_CLIENTE => [
                                    ($bg = 'bg-red-100 border-red-400 text-red-700'),
                                    ($mensaje = 'El usuario rechazó la propuesta.'),
                                ],
                                Cita::ESTADO_PAGADA => [
                                    ($bg = 'bg-green-100 border-green-400 text-green-700'),
                                    ($mensaje = 'La factura ha sido pagada.'),
                                ],
                                Cita::ESTADO_FECHA_ACEPTADA_CLIENTE => [
                                    ($bg = 'bg-blue-100 border-blue-400 text-blue-700'),
                                    ($mensaje = 'El cliente aceptó la nueva fecha.'),
                                ],
                                default => [($bg = 'bg-gray-100 border-gray-300 text-gray-700'), ($mensaje = '')],
                            };
                        @endphp
                        <div
                            class="border rounded-lg p-4 shadow-sm flex items-center gap-4 relative {{ $bg }}">
                            @if ($cita->estado === Cita::ESTADO_FECHA_ACEPTADA_CLIENTE)
                                <button wire:click="aceptarFechaPropuesta({{ $cita->id_cita }})"
                                    class="absolute top-2 right-2 font-bold text-lg">&times;</button>
                            @else
                                <button wire:click="terminarNotificacion({{ $cita->id_cita }})"
                                    class="absolute top-2 right-2 font-bold text-lg">&times;</button>
                            @endif
                            <div class="flex-1">
                                <p class="text-sm font-semibold">Usuario: {{ $cita->coche->usuario->nombre }}</p>
                                <p class="text-sm">Coche: {{ $cita->coche->marca }} {{ $cita->coche->modelo }}</p>
                                <p class="text-sm">Fecha: {{ $cita->fecha }}</p>
                                <p class="text-sm font-medium mt-1">{{ $mensaje }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        @endif

        {{-- PAGOS --}}
        @if ($tab === 'pagos')
            @if ($pagos->isEmpty())
                <div class="text-center py-10">
                    <div class="text-4xl mb-3">💰</div>
                    <p class="text-gray-500">No hay pagos pendientes en taller.</p>
                </div>
            @else
                <div class="flex flex-wrap gap-4 pt-2">
                    @foreach ($pagos as $cita)
                        @php
                            $detalles = is_string($cita->detalles)
                                ? json_decode($cita->detalles, true)
                                : $cita->detalles;
                        @endphp
                        <div
                            class="bg-white border-2 border-purple-200 rounded-lg shadow-sm p-4 w-64 flex flex-col justify-between hover:border-purple-400 hover:shadow-md transition-all">
                            <div class="flex items-center justify-between mb-3">
                                <span
                                    class="text-xs font-medium bg-purple-100 text-purple-700 px-2 py-0.5 rounded-full">
                                    💳 Pago en taller
                                </span>
                            </div>
                            <div class="space-y-1 mb-3">
                                <p class="text-sm font-semibold text-gray-900 truncate">👤
                                    {{ $cita->coche->usuario->nombre }}</p>
                                <p class="text-sm text-gray-600">🚗 {{ $cita->coche->marca }}
                                    {{ $cita->coche->modelo }}</p>
                                <p class="text-xs text-gray-500">
                                    📅 {{ \Carbon\Carbon::parse($cita->fecha)->locale('es')->isoFormat('D MMM YYYY') }}
                                </p>
                            </div>
                            @if ($detalles && is_array($detalles) && count($detalles) > 0)
                                <div class="bg-gray-50 rounded-lg p-3 mb-3 space-y-1">
                                    @foreach ($detalles as $detalle)
                                        <div class="flex justify-between text-xs text-gray-600">
                                            <span class="truncate mr-2">{{ $detalle['nombre'] ?? 'Servicio' }}</span>
                                            <span
                                                class="font-medium whitespace-nowrap">{{ number_format(floatval($detalle['precio'] ?? 0), 2) }}€</span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                            <div class="border-t border-gray-200 pt-2 mb-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-bold text-gray-700">Total a abonar</span>
                                    <span class="text-base font-bold text-purple-700">
                                        {{ $cita->total ? number_format($cita->total, 2) . '€' : '—' }}
                                    </span>
                                </div>
                            </div>
                            <form action="{{ route('cita.marcar-pagado', $cita->id_cita) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <button type="submit"
                                    class="w-full px-3 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 transition flex items-center justify-center gap-2">
                                    ✓ Marcar como pagado
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @endif
        @endif

    </div>

    {{-- MODAL DETALLE --}}
    @if ($abiertoDetalle && $citaDetalle)
        <div class="fixed inset-0 flex items-center justify-center z-50" style="background: rgba(0,0,0,0.4)">
            <div class="bg-white rounded-xl shadow-xl w-[520px] p-6 relative">

                <button wire:click="cerrarDetalle"
                    class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 text-xl">&times;</button>

                <h3 class="text-xl font-bold mb-6">Detalles cita</h3>

                <div class="grid grid-cols-2 gap-6 mb-6 text-sm">
                    <div class="space-y-3">
                        <p><span class="font-semibold text-gray-700">Usuario:</span>
                            {{ $citaDetalle->coche->usuario->nombre }}</p>
                        <p><span class="font-semibold text-gray-700">Marca:</span> {{ $citaDetalle->coche->marca }}</p>
                    </div>
                    <div class="space-y-3">
                        <p><span class="font-semibold text-gray-700">Fecha:</span> {{ $citaDetalle->fecha }}</p>
                        <p><span class="font-semibold text-gray-700">Modelo:</span> {{ $citaDetalle->coche->modelo }}
                        </p>
                        <p><span class="font-semibold text-gray-700">Tramo:</span>
                            {{ $citaDetalle->tramo_horario === 'manana' ? 'Mañana' : 'Tarde' }}</p>
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-semibold mb-2 text-gray-700">Detalles de la solicitud</label>
                    <textarea readonly class="w-full h-24 p-3 border rounded-lg bg-gray-100 text-sm resize-none">{{ $citaDetalle->motivo }}</textarea>
                </div>

                <div class="flex gap-3 justify-end mb-4">
                    <button wire:click="rechazarCita" wire:confirm="¿Seguro que quieres rechazar esta cita?"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                        Rechazar
                    </button>
                    <button wire:click="$set('mostrarNuevaFecha', true)"
                        class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition">
                        Proponer nueva fecha
                    </button>
                    <button wire:click="aceptarCita"
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                        Aceptar
                    </button>
                </div>

                @if ($mostrarNuevaFecha)
                    <div class="border-t pt-4 mt-2">
                        <label class="block text-sm font-semibold mb-2 text-gray-700">Nueva fecha propuesta</label>
                        <div class="flex gap-3 mb-3">
                            <input type="date" wire:model="nuevaFecha"
                                class="flex-1 px-3 py-2 border rounded-lg focus:ring-2 focus:ring-yellow-500">
                        </div>
                        <label class="block text-sm font-semibold mb-2 text-gray-700">Tramo horario</label>
                        <div class="flex gap-3">
                            <select wire:model="tramoHorario"
                                class="flex-1 px-3 py-2 border rounded-lg focus:ring-2 focus:ring-yellow-500">
                                <option value="manana">Mañana</option>
                                <option value="tarde">Tarde</option>
                            </select>
                            <button wire:click="proponerFecha"
                                class="px-6 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition whitespace-nowrap">
                                Confirmar
                            </button>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    @endif
</div>
