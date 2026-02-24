<x-layouts::app :title="__('Gestión de Citas')">
    <script src="https://unpkg.com/flowbite@latest/dist/flowbite.min.js"></script>

    @php
        $taller = auth()->user()->taller ?? null;
    @endphp

    @if (!$taller)

        <div class="flex items-center justify-center min-h-[70vh]">
            <div class="bg-white shadow-lg rounded-xl p-10 text-center max-w-xl border">

                <div class="text-6xl mb-4">⚠️</div>

                <h2 class="text-2xl font-bold text-gray-800 mb-4">
                    No tienes un taller registrado
                </h2>

                <p class="text-gray-600 mb-6">
                    Para poder gestionar citas necesitas crear primero tu taller.
                </p>

                <a href="{{ route('mi-taller') }}"
                    class="inline-block px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Crear mi taller
                </a>

            </div>
        </div>
    @else
        @php
            $notificaciones = $citas->filter(fn($cita) => in_array($cita->estado, [-2, 4, 11]));

            $citasPendientesPago = $citas->where('estado', \App\Models\Cita::ESTADO_ESPARA_PAGO_TALLER);

            $resumenCitas = $citas
                ->where('estado', \App\Models\Cita::ESTADO_ACEPTADO)
                ->groupBy('fecha')
                ->map(function ($citasPorDia, $fecha) {
                    return [
                        'fecha' => $fecha,
                        'total' => $citasPorDia->count(),
                    ];
                })
                ->values();

            $diasBloqueados = \App\Models\Dia::where('id_taller', $taller->id_taller)
                ->where('estado', '=', 1)
                ->pluck('fecha')
                ->values();

        @endphp

        <div class="flex w-full">

            {{-- Sidebar --}}
            <x-sidebar-taller />

            {{-- Contenido principal --}}
            <div class="flex-1 p-6" x-data="{
                tab: 'solicitudes',
                abiertoDetalle: false,
                marca: '',
                citaId: null,
                modelo: '',
                fecha: '',
                usuario: '',
                detalles: '',
                abierto: {{ isset($fecha) ? 'true' : 'false' }},
                fechaSeleccionada: '{{ $fecha ?? '' }}',
                mostrarNuevaFecha: false,
                nuevaFecha: '',
                permitirCitas: 'si',
                cantidadCitas: {{ isset($citas) ? count($citas) : 0 }},
                tramo: '',
            }">

                {{-- Calendario --}}
                <div class="bg-white shadow-lg rounded-lg p-6 mb-6">
                    <h2 class="text-2xl font-bold mb-6">Calendario de Citas</h2>
                    <div id="calendar"></div>
                    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">
                    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>


                    {{-- Tabs --}}
                    <div class="bg-white shadow-lg rounded-lg p-6">

                        {{-- Botones de Tabs --}}
                        <div class="flex border-b border-gray-200 mb-4">
                            <button
                                :class="tab === 'solicitudes' ? 'border-b-2 border-blue-600 text-blue-600' :
                                    'text-gray-500'"
                                class="px-4 py-2 font-medium focus:outline-none" @click="tab = 'solicitudes'">
                                Solicitudes de Citas
                            </button>
                            <button
                                :class="tab === 'notificaciones' ?
                                    'border-b-2 border-blue-600 text-blue-600' :
                                    'text-gray-500'"
                                class="px-4 py-2 font-medium focus:outline-none relative"
                                @click="tab = 'notificaciones'">

                                Notificaciones

                                @if ($notificaciones->count() > 0)
                                    <span
                                        class="absolute -top-1 -right-2 bg-red-600 text-white text-xs
                            w-5 h-5 flex items-center justify-center
                            rounded-full">
                                        {{ $notificaciones->count() }}
                                    </span>
                                @endif

                            </button>

                            <button
                                :class="tab === 'pagos' ? 'border-b-2 border-blue-600 text-blue-600' :
                                    'text-gray-500'"
                                class="px-4 py-2 font-medium focus:outline-none relative" @click="tab = 'pagos'">

                                Pagos en Taller

                                @if ($citasPendientesPago->count() > 0)
                                    <span
                                        class="absolute -top-1 -right-2 bg-purple-600 text-white text-xs
                            w-5 h-5 flex items-center justify-center
                            rounded-full">
                                        {{ $citasPendientesPago->count() }}
                                    </span>
                                @endif

                            </button>

                        </div>

                        {{-- Contenido de Tabs --}}
                        <div>
                            {{-- Solicitudes de Citas --}}
                            <div x-show="tab === 'solicitudes'">
                                @php $haySolicitudes = false; @endphp
                                @foreach ($citas as $cita)
                                    @if ($cita->estado === 0)
                                        @php $haySolicitudes = true; @endphp
                                    @endif
                                @endforeach

                                @if (!$haySolicitudes)
                                    <p class="text-gray-500 text-center py-6">No hay solicitudes pendientes.</p>
                                @else
                                    {{-- Grid de cards en fila, con wrap --}}
                                    <div class="flex flex-wrap gap-4 pt-2">
                                        @foreach ($citas as $cita)
                                            @if ($cita->estado === 0)
                                                <div
                                                    class="bg-white border-2 border-gray-200 rounded-lg shadow-sm p-4 w-56 flex flex-col justify-between hover:border-blue-300 hover:shadow-md transition-all">
                                                    <div class="space-y-1 mb-3">
                                                        <p class="text-sm font-semibold text-gray-900 truncate">
                                                            {{ $cita->coche->marca }} {{ $cita->coche->modelo }}
                                                        </p>
                                                        <p class="text-xs text-gray-500">
                                                            👤 {{ $cita->coche->usuario->nombre }}
                                                        </p>
                                                        <p class="text-xs text-gray-500">
                                                            📅
                                                            {{ \Carbon\Carbon::parse($cita->fecha)->locale('es')->isoFormat('D MMM YYYY') }}
                                                        </p>
                                                        <p class="text-xs text-gray-500">
                                                            🕐
                                                            @if ($cita->tramo_horario === 'manana')
                                                                Mañana
                                                            @elseif($cita->tramo_horario === 'tarde')
                                                                Tarde
                                                            @else
                                                                {{ $cita->tramo_horario }}
                                                            @endif
                                                        </p>
                                                    </div>
                                                    <button
                                                        @click="abiertoDetalle = true;
                                                            marca='{{ $cita->coche->marca }}';
                                                            modelo='{{ $cita->coche->modelo }}';
                                                            fecha='{{ $cita->fecha }}';
                                                            usuario='{{ $cita->coche->usuario->nombre }}';
                                                            detalles='{{ $cita->motivo }}';
                                                            citaId='{{ $cita->id_cita }}';
                                                            tramo='{{ $cita->tramo_horario }}';"
                                                        class="w-full px-3 py-1.5 text-xs font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition">
                                                        Ver detalles
                                                    </button>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            {{-- Notificaciones --}}
                            <div x-show="tab === 'notificaciones'" class="space-y-4">

                                @if ($notificaciones->isNotEmpty())
                                    @foreach ($notificaciones as $cita)
                                        @php
                                            switch ($cita->estado) {
                                                case \App\Models\Cita::ESTADO_RECHAZADO_POR_CLIENTE:
                                                    $bg = 'bg-red-100 border-red-400 text-red-700';
                                                    $mensaje = 'El usuario rechazó la propuesta.';
                                                    break;
                                                case \App\Models\Cita::ESTADO_PAGADA:
                                                    $bg = 'bg-green-100 border-green-400 text-green-700';
                                                    $mensaje = 'La factura ha sido pagada.';
                                                    break;
                                                case \App\Models\Cita::ESTADO_FECHA_ACEPTADA_CLIENTE:
                                                    $bg = 'bg-blue-100 border-blue-400 text-blue-700';
                                                    $mensaje = 'El cliente aceptó la nueva fecha de la cita.';
                                                    break;
                                                default:
                                                    $bg = 'bg-gray-100 border-gray-300 text-gray-700';
                                                    $mensaje = '';
                                            }
                                        @endphp

                                        <div
                                            class="border rounded-lg p-4 shadow-sm flex items-center gap-4 relative {{ $bg }}">

                                            {{-- Botón de eliminar --}}
                                            @if ($cita->estado === \App\Models\Cita::ESTADO_FECHA_ACEPTADA_CLIENTE)
                                                <form action="{{ route('cita.aceptar', $cita->id_cita) }}"
                                                    method="POST" class="absolute top-2 right-2">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="font-bold text-lg">&times;</button>
                                                </form>
                                            @else
                                                <form action="{{ route('cita.terminar', $cita->id_cita) }}"
                                                    method="POST" class="absolute top-2 right-2">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="font-bold text-lg">&times;</button>
                                                </form>
                                            @endif

                                            <div class="flex-1">
                                                <p class="text-sm font-semibold">
                                                    Usuario: {{ $cita->coche->usuario->nombre }}
                                                </p>
                                                <p class="text-sm">
                                                    Coche: {{ $cita->coche->marca }}
                                                    {{ $cita->coche->modelo }}
                                                </p>
                                                <p class="text-sm">
                                                    Fecha de cita: {{ $cita->fecha }}
                                                </p>
                                                <p class="text-sm font-medium mt-1">
                                                    {{ $mensaje }}
                                                </p>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <p class="text-gray-600 text-center py-6">No hay notificaciones por ahora.</p>
                                @endif

                            </div>

                            {{-- Pagos en Taller --}}
                            <div x-show="tab === 'pagos'">

                                @if ($citasPendientesPago->isEmpty())
                                    <div class="text-center py-10">
                                        <div class="text-4xl mb-3">💰</div>
                                        <p class="text-gray-500">No hay pagos pendientes en taller.</p>
                                    </div>
                                @else
                                    <div class="flex flex-wrap gap-4 pt-2">
                                        @foreach ($citasPendientesPago as $cita)
                                            @php
                                                $detalles = is_string($cita->detalles)
                                                    ? json_decode($cita->detalles, true)
                                                    : $cita->detalles;
                                            @endphp
                                            <div
                                                class="bg-white border-2 border-purple-200 rounded-lg shadow-sm p-4 w-64 flex flex-col justify-between hover:border-purple-400 hover:shadow-md transition-all">

                                                {{-- Header badge --}}
                                                <div class="flex items-center justify-between mb-3">
                                                    <span
                                                        class="text-xs font-medium bg-purple-100 text-purple-700 px-2 py-0.5 rounded-full">
                                                        💳 Pago en taller
                                                    </span>
                                                </div>

                                                {{-- Info cliente y coche --}}
                                                <div class="space-y-1 mb-3">
                                                    <p class="text-sm font-semibold text-gray-900 truncate">
                                                        👤 {{ $cita->coche->usuario->nombre }}
                                                    </p>
                                                    <p class="text-sm text-gray-600">
                                                        🚗 {{ $cita->coche->marca }} {{ $cita->coche->modelo }}
                                                        @if ($cita->coche->anyo)
                                                            ({{ $cita->coche->anyo }})
                                                        @endif
                                                    </p>
                                                    <p class="text-xs text-gray-500">
                                                        📅
                                                        {{ \Carbon\Carbon::parse($cita->fecha)->locale('es')->isoFormat('D MMM YYYY') }}
                                                    </p>
                                                </div>

                                                {{-- Desglose factura si hay detalles --}}
                                                @if ($detalles && is_array($detalles) && count($detalles) > 0)
                                                    <div class="bg-gray-50 rounded-lg p-3 mb-3 space-y-1">
                                                        @foreach ($detalles as $detalle)
                                                            <div class="flex justify-between text-xs text-gray-600">
                                                                <span
                                                                    class="truncate mr-2">{{ $detalle['nombre'] ?? 'Servicio' }}</span>
                                                                <span
                                                                    class="font-medium whitespace-nowrap">{{ number_format(floatval($detalle['precio'] ?? 0), 2) }}€</span>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif

                                                {{-- Total --}}
                                                <div class="border-t border-gray-200 pt-2 mb-4">
                                                    <div class="flex justify-between items-center">
                                                        <span class="text-sm font-bold text-gray-700">Total a
                                                            abonar</span>
                                                        <span class="text-base font-bold text-purple-700">
                                                            {{ $cita->total ? number_format($cita->total, 2) . '€' : '—' }}
                                                        </span>
                                                    </div>
                                                </div>

                                                {{-- Botón marcar como pagado --}}
                                                <form action="{{ route('cita.marcar-pagado', $cita->id_cita) }}"
                                                    method="POST">
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

                            </div>

                        </div>

                    </div>

                    {{-- Modal de detalles de cada cita --}}
                    <div x-show="abiertoDetalle" x-cloak style="background: rgba(0,0,0,0.4)"
                        class="fixed inset-0 flex items-center justify-center z-50">

                        <div class="bg-white rounded-xl shadow-xl w-[520px] p-6 relative">

                            <!-- Cerrar -->
                            <button @click="abiertoDetalle = false; mostrarNuevaFecha = false"
                                class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 text-xl">
                                &times;
                            </button>

                            <h3 class="text-xl font-bold text-left mb-6">Detalles cita</h3>

                            <!-- INFO -->
                            <div class="grid grid-cols-2 gap-6 mb-6 text-sm">
                                <div class="space-y-3">
                                    <p><span class="font-semibold text-gray-700">Usuario:</span>
                                        <span x-text="usuario"></span>
                                    </p>
                                    <p><span class="font-semibold text-gray-700">Marca:</span>
                                        <span x-text="marca"></span>
                                    </p>
                                </div>
                                <div class="space-y-3">
                                    <p><span class="font-semibold text-gray-700">Fecha:</span>
                                        <span x-text="fecha"></span>
                                    </p>
                                    <p><span class="font-semibold text-gray-700">Modelo:</span>
                                        <span x-text="modelo"></span>
                                    </p>
                                    <p><span class="font-semibold text-gray-700">Tramo:</span>
                                        <span x-text="tramo === 'manana' ? 'Mañana' : 'Tarde'"></span>
                                    </p>
                                </div>
                            </div>

                            <!-- DETALLES -->
                            <div class="mb-6">
                                <label class="block text-sm font-semibold mb-2 text-gray-700">
                                    Detalles de la solicitud
                                </label>
                                <textarea readonly class="w-full h-24 p-3 border rounded-lg bg-gray-100 text-sm resize-none" x-text="detalles"></textarea>
                            </div>

                            <!-- BOTONES -->
                            <div class="flex gap-3 justify-end mb-4">

                                <!-- RECHAZAR -->
                                <form :action="'/citas/' + citaId + '/rechazar'" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit"
                                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                                        Rechazar
                                    </button>
                                </form>

                                <!-- PROPONER NUEVA FECHA -->
                                <button @click="mostrarNuevaFecha = !mostrarNuevaFecha"
                                    class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition">
                                    Proponer nueva fecha
                                </button>

                                <!-- ACEPTAR -->
                                <form :action="'/citas/' + citaId + '/aceptar'" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit"
                                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                                        Aceptar
                                    </button>
                                </form>

                            </div>

                            <!-- ZONA NUEVA FECHA -->
                            <div x-show="mostrarNuevaFecha" x-transition class="border-t pt-4 mt-2">

                                <form :action="'/citas/' + citaId + '/proponer-fecha'" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <label class="block text-sm font-semibold mb-2 text-gray-700">
                                        Nueva fecha propuesta
                                    </label>

                                    <div class="flex gap-3 mb-3">
                                        <input type="date" name="nueva_fecha" x-model="nuevaFecha"
                                            class="flex-1 px-3 py-2 border rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
                                    </div>

                                    <label class="block text-sm font-semibold mb-2 text-gray-700">
                                        Tramo horario
                                    </label>

                                    <div class="flex gap-3">
                                        <select name="tramo_horario" x-model="tramo"
                                            class="flex-1 px-3 py-2 border rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
                                            <option value="manana">Mañana</option>
                                            <option value="tarde">Tarde</option>
                                        </select>

                                        <button type="submit"
                                            class="px-6 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition whitespace-nowrap">
                                            Confirmar
                                        </button>
                                    </div>

                                </form>

                            </div>

                        </div>
                    </div>
                    <livewire:taller.modal-detalle-cita />

                    {{-- Modal del día con las citas --}}
                    <livewire:taller.modal-dia-citas />

                </div>

            </div>

        </div>

        <script>
            const resumenCitas = @json($resumenCitas);
            const diasBloqueados = @json($diasBloqueados);

            function iniciarCalendario() {
                let calendarEl = document.getElementById('calendar');
                if (!calendarEl) return;
                if (calendarEl._calendar) return;

                if (typeof FullCalendar === 'undefined') {
                    setTimeout(iniciarCalendario, 100);
                    return;
                }

                let eventosResumen = resumenCitas.map(dia => ({
                    title: '🔧 ' + dia.total,
                    start: dia.fecha,
                    allDay: true
                }));

                let eventosBloqueados = diasBloqueados.map(fecha => ({
                    title: '🚫 No disponible',
                    start: fecha,
                    allDay: true,
                    display: 'background',
                    backgroundColor: '#ef4444',
                    classNames: ['dia-bloqueado']
                }));

                let calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    locale: 'es',
                    height: 650,
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: ''
                    },
                    events: [...eventosResumen, ...eventosBloqueados],
                    dateClick: function(info) {
                        window.dispatchEvent(new CustomEvent('abrir-modal-dia', {
                            detail: {
                                fecha: info.dateStr
                            }
                        }));
                    }
                });

                calendar.render();
                calendarEl._calendar = calendar;
            }

            function esperarYIniciar() {
                const calendarEl = document.getElementById('calendar');
                if (calendarEl && typeof FullCalendar !== 'undefined') {
                    iniciarCalendario();
                } else {
                    setTimeout(esperarYIniciar, 100);
                }
            }

            const observer = new MutationObserver(() => {
                const calendarEl = document.getElementById('calendar');
                if (calendarEl && typeof FullCalendar !== 'undefined') {
                    observer.disconnect();
                    iniciarCalendario();
                }
            });
            observer.observe(document.body, {
                childList: true,
                subtree: true
            });

            document.addEventListener('DOMContentLoaded', () => setTimeout(esperarYIniciar, 100));
            document.addEventListener('livewire:init', () => setTimeout(esperarYIniciar, 100));
            document.addEventListener('livewire:navigated', () => {
                const calendarEl = document.getElementById('calendar');
                if (calendarEl?._calendar) {
                    calendarEl._calendar.destroy();
                    calendarEl._calendar = null;
                }
                setTimeout(esperarYIniciar, 100);
            });

            window.addEventListener('calendario-actualizado', async () => {
                const calendarEl = document.getElementById('calendar');
                if (!calendarEl) return;

                // Pedir datos frescos al servidor
                const res = await fetch('/taller/calendario-datos');
                const data = await res.json();

                // Destruir el calendario actual
                if (calendarEl._calendar) {
                    calendarEl._calendar.destroy();
                    calendarEl._calendar = null;
                }

                // Reconstruir con datos nuevos
                const eventosResumen = data.resumenCitas.map(dia => ({
                    title: '🔧 ' + dia.total,
                    start: dia.fecha,
                    allDay: true
                }));

                const eventosBloqueados = data.diasBloqueados.map(fecha => ({
                    title: '🚫 No disponible',
                    start: fecha,
                    allDay: true,
                    display: 'background',
                    backgroundColor: '#ef4444',
                    classNames: ['dia-bloqueado']
                }));

                const calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    locale: 'es',
                    height: 650,
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: ''
                    },
                    events: [...eventosResumen, ...eventosBloqueados],
                    dateClick: function(info) {
                        window.dispatchEvent(new CustomEvent('abrir-modal-dia', {
                            detail: {
                                fecha: info.dateStr
                            }
                        }));
                    }
                });

                calendar.render();
                calendarEl._calendar = calendar;
            });
        </script>

    @endif
</x-layouts::app>
