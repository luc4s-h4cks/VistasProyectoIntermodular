<?php

use Livewire\Component;
use Livewire\Attributes\Validate;
use App\Models\Cita;
use App\Http\Controllers\DiaController;


new class extends Component {
    public Cita $cita;
    public bool $mostrarInputFecha = false;

    #[
        Validate(
            'required|date|after:today',
            message: [
                'required' => 'Debes seleccionar una fecha',
                'after' => 'La fecha debe ser posterior a hoy',
            ],
        ),
    ]
    public string $nuevaFecha = '';

    public function toggleInputFecha()
    {
        $this->mostrarInputFecha = !$this->mostrarInputFecha;
        if (!$this->mostrarInputFecha) {
            $this->nuevaFecha = '';
            $this->resetValidation();
        }
    }

    public function proponerNuevaFecha()
    {
        $this->validate();

        $idTaller = $this->cita->id_taller;

        $diaController = new DiaController();
        $dia = $diaController->existeDia($this->cita->id_taller, $this->nuevaFecha);

        // Actualizar la fecha de la cita
        $this->cita->update([
            'fecha' => $this->nuevaFecha,
            'estado' => Cita::ESTADO_SOLICITADO,
        ]);

        session()->flash('mensaje', 'Nueva fecha propuesta correctamente');

        $this->mostrarInputFecha = false;
        $this->nuevaFecha = '';
    }
};
?>

<div class="block p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-50">
    @php
        // Decodificar el JSON de detalles si es string
        $detalles = is_string($cita->detalles) ? json_decode($cita->detalles, true) : $cita->detalles;
    @endphp

    <div class="flex items-start justify-between">
        <div class="flex-1">
            {{-- Fecha de la cita --}}
            <div class="flex items-center mb-3">
                <svg class="w-5 h-5 text-gray-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                        clip-rule="evenodd" />
                </svg>
                <p class="text-sm font-semibold text-gray-900">
                    {{ \Carbon\Carbon::parse($cita->fecha)->locale('es')->isoFormat('D [de] MMMM [de] YYYY') }}
                    @if ($cita->tramo_horario)
                        - {{ $cita->tramo_horario }}
                    @endif
                </p>
            </div>

            {{-- Alerta de nueva fecha propuesta --}}
            @if ($cita->estado == App\Models\Cita::ESTADO_FECHA_PROPUESTA)
                <div class="mb-3 p-3 bg-orange-50 border-l-4 border-orange-400 rounded">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-orange-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-orange-800">
                                <span class="font-semibold">¡Cambio de fecha!</span>
                                El taller ha propuesto una nueva fecha para tu cita. ¿Te viene bien esta fecha?
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Taller --}}
            <div class="flex items-center mb-2">
                <svg class="w-5 h-5 text-gray-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                        clip-rule="evenodd" />
                </svg>
                <p class="text-sm text-gray-700">
                    <span class="font-medium">Taller:</span>
                    {{ $cita->taller->nombre ?? 'Sin asignar' }}
                </p>
            </div>

            {{-- Coche --}}
            <div class="flex items-center mb-3">
                <svg class="w-5 h-5 text-gray-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" />
                    <path
                        d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1v-5a1 1 0 00-.293-.707l-2-2A1 1 0 0015 7h-1z" />
                </svg>
                <p class="text-sm text-gray-700">
                    <span class="font-medium">Vehículo:</span>
                    {{ $cita->coche->marca }} {{ $cita->coche->modelo }}
                    @if ($cita->coche->anyo)
                        ({{ $cita->coche->anyo }})
                    @endif
                </p>
            </div>

            {{-- Motivo --}}
            @if ($cita->motivo)
                <p class="text-sm text-gray-600 mb-3">
                    <span class="font-medium">Motivo:</span> {{ $cita->motivo }}
                </p>
            @endif

            {{-- FACTURA --}}
            @if ($detalles && is_array($detalles) && count($detalles) > 0)
                <div class="mt-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <div class="flex items-center mb-3">
                        <svg class="w-5 h-5 text-gray-700 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9 2a2 2 0 00-2 2v8a2 2 0 002 2h6a2 2 0 002-2V6.414A2 2 0 0016.414 5L14 2.586A2 2 0 0012.586 2H9z" />
                            <path d="M3 8a2 2 0 012-2v10h8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z" />
                        </svg>
                        <h4 class="text-sm font-bold text-gray-900">Factura</h4>
                    </div>

                    <div class="space-y-2 mb-3">
                        @foreach ($detalles as $detalle)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-700">{{ $detalle['nombre'] ?? 'Sin nombre' }}</span>
                                <span
                                    class="font-medium text-gray-900">{{ number_format(floatval($detalle['precio'] ?? 0), 2) }}€</span>
                            </div>
                        @endforeach
                    </div>

                    <div class="border-t border-gray-300 pt-3 space-y-2">
                        @if ($cita->subtotal)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Subtotal:</span>
                                <span class="text-gray-900">{{ number_format($cita->subtotal, 2) }}€</span>
                            </div>
                        @endif

                        @if ($cita->iva)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">IVA (21%):</span>
                                <span class="text-gray-900">{{ number_format($cita->iva, 2) }}€</span>
                            </div>
                        @endif

                        @if ($cita->total)
                            <div class="flex justify-between text-base font-bold border-t border-gray-300 pt-2">
                                <span class="text-gray-900">Total:</span>
                                <span class="text-gray-900">{{ number_format($cita->total, 2) }}€</span>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        {{-- Estado --}}
        <div>
            @if ($cita->estado == App\Models\Cita::ESTADO_SOLICITADO)
                <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-3 py-1 rounded-full">
                    Pendiente
                </span>
            @elseif($cita->estado == App\Models\Cita::ESTADO_ACEPTADO)
                <span class="bg-green-100 text-green-800 text-xs font-medium px-3 py-1 rounded-full">
                    Aceptada
                </span>
            @elseif(
                $cita->estado == App\Models\Cita::ESTADO_RECHAZADO_POR_TALLER ||
                    $cita->estado == App\Models\Cita::ESTADO_RECHAZADO_POR_CLIENTE)
                <span class="bg-red-100 text-red-800 text-xs font-medium px-3 py-1 rounded-full">
                    Rechazada
                </span>
            @elseif($cita->estado == App\Models\Cita::ESTADO_TEMINADO)
                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-3 py-1 rounded-full">
                    Completada
                </span>
            @elseif($cita->estado == App\Models\Cita::ESTADO_ESPERANDO_PAGO)
                <span class="bg-purple-100 text-purple-800 text-xs font-medium px-3 py-1 rounded-full">
                    Esperando pago
                </span>
            @elseif($cita->estado == App\Models\Cita::ESTADO_PAGADA)
                <span class="bg-indigo-100 text-indigo-800 text-xs font-medium px-3 py-1 rounded-full">
                    Pagada
                </span>
            @elseif($cita->estado == App\Models\Cita::ESTADO_FECHA_PROPUESTA)
                <span class="bg-orange-100 text-orange-800 text-xs font-medium px-3 py-1 rounded-full">
                    Nueva fecha propuesta
                </span>
            @elseif($cita->estado == App\Models\Cita::ESTADO_FECHA_ACEPTADA_CLIENTE)
                <span class="bg-teal-100 text-teal-800 text-xs font-medium px-3 py-1 rounded-full">
                    Fecha aceptada
                </span>
            @else
                <span class="bg-gray-100 text-gray-800 text-xs font-medium px-3 py-1 rounded-full">
                    Desconocido
                </span>
            @endif
        </div>
    </div>

    {{-- Mensaje de éxito --}}
    @if (session()->has('mensaje'))
        <div class="mt-4 p-3 bg-green-50 border-l-4 border-green-400 rounded">
            <p class="text-sm text-green-800">{{ session('mensaje') }}</p>
        </div>
    @endif

    {{-- Botones de acción --}}
    <div class="mt-4 pt-4 border-t border-gray-200">
        {{-- Solo mostrar cuando el estado es ESTADO_FECHA_PROPUESTA (10) --}}
        @if ($cita->estado == App\Models\Cita::ESTADO_FECHA_PROPUESTA)
            <div class="mb-4">
                <button wire:click="toggleInputFecha" type="button"
                    class="text-yellow-600 hover:text-yellow-800 text-sm font-medium mb-2">
                    📅 Proponer nueva fecha
                </button>

                @if ($mostrarInputFecha)
                    <div class="mt-3 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <label class="block text-sm font-semibold mb-2 text-gray-700">
                            Selecciona la nueva fecha
                        </label>

                        <div class="flex gap-3">
                            <input type="date" wire:model="nuevaFecha"
                                class="flex-1 px-3 py-2 border rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">

                            <button wire:click="proponerNuevaFecha" type="button"
                                class="px-6 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition whitespace-nowrap">
                                Proponer
                            </button>
                        </div>

                        @error('nuevaFecha')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                @endif
            </div>

            {{-- Botones de aceptar/rechazar --}}
            <div class="flex gap-3">
                <form action="{{ route('cita.aceptar', $cita->id_cita) }}" method="POST" class="flex-1">
                    @csrf
                    @method('PUT')
                    <button type="submit"
                        class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-sm font-medium">
                        ✓ Aceptar fecha actual
                    </button>
                </form>

                <form action="{{ route('cita.rechazar', $cita->id_cita) }}" method="POST" class="flex-1">
                    @csrf
                    @method('PUT')
                    <button type="submit"
                        class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition text-sm font-medium">
                        ✗ Rechazar y cancelar cita
                    </button>
                </form>
            </div>
        @elseif($cita->estado == App\Models\Cita::ESTADO_ESPERANDO_PAGO && $cita->total)
            {{-- Botón para pagar --}}
            <form action="" method="POST">
                @csrf
                <button type="submit"
                    class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm font-medium">
                    💳 Pagar {{ number_format($cita->total, 2) }}€
                </button>
            </form>
        @else
            {{-- Botón normal para otras citas --}}
            <button type="button" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                Ver detalles →
            </button>
        @endif
    </div>
</div>
