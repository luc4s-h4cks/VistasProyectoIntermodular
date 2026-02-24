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


                    <livewire:taller.tabs />

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
