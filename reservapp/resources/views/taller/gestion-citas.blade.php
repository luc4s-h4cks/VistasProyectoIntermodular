<x-layouts::app :title="__('Gestión de Citas')">
    <script src="https://unpkg.com/flowbite@latest/dist/flowbite.min.js"></script>

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
        }">

            {{-- Calendario --}}
            <div class="bg-white shadow-lg rounded-lg p-6 mb-6">
                <h2 class="text-2xl font-bold mb-6">Calendario de Citas</h2>
                <div id="calendar"></div>
            </div>

            {{-- Modal del día --}}
            <div id="modal-dia" x-show="abierto" x-cloak
                class="fixed inset-0 bg-black bg-opacity-20 flex items-center justify-center z-50">

                <div class="bg-white rounded-xl shadow-xl w-[600px] max-h-[90vh] flex flex-col p-6 relative">

                    {{-- Botón cerrar --}}
                    <<button @click="window.location.href='{{ route('gestion-citas') }}'"
                        class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 text-2xl font-bold">
                        &times;
                        </button>
                        {{-- Título --}}
                        <h2 class="text-2xl font-bold mb-6 text-center">
                            DIA <span x-text="fechaSeleccionada"></span>
                        </h2>

                        {{-- Select y cantidad de citas --}}
                        <div class="flex items-center justify-between mb-4 pb-4 border-b">
                            <div class="flex items-center gap-2">
                                <select x-model="permitirCitas"
                                    class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="si">Permitir más citas</option>
                                    <option value="no">No permitir más citas</option>
                                </select>
                            </div>
                            <div class="text-sm font-medium text-gray-700">
                                Cantidad de citas aceptadas:
                                <span class="font-bold text-blue-600" x-text="cantidadCitas"></span>
                            </div>
                        </div>

                        {{-- Zona scrolleable con las citas --}}
                        <div class="flex-1 overflow-y-auto space-y-4 pr-2" style="max-height: 500px;">

                            @if (isset($citas) && $citas->count() > 0)
                                @foreach ($citas as $cita)
                                    <div class="border-2 border-gray-300 rounded-lg p-4">
                                        <div class="flex gap-4">

                                            {{-- Imagen del coche --}}
                                            <div class="flex-shrink-0">
                                                <div
                                                    class="w-24 h-24 bg-gray-300 rounded-full flex items-center justify-center text-gray-500">
                                                    img
                                                </div>
                                            </div>

                                            {{-- Información --}}
                                            <div class="flex-1 grid grid-cols-2 gap-4">

                                                {{-- Columna izquierda --}}
                                                <div class="space-y-1">
                                                    <p class="text-sm"><span class="font-semibold">Usuario:</span>
                                                        {{ $cita->coche->usuario->nombre }}</p>
                                                    <p class="text-sm"><span class="font-semibold">Marca:</span>
                                                        {{ $cita->coche->marca }}</p>
                                                    <p class="text-sm"><span class="font-semibold">Modelo:</span>
                                                        {{ $cita->coche->modelo }}</p>
                                                </div>

                                                {{-- Columna derecha --}}
                                                <div>
                                                    <p class="text-sm font-semibold mb-1">Motivo</p>
                                                    <div class="text-xs bg-gray-100 p-2 rounded h-16 overflow-y-auto">
                                                        {{ $cita->motivo }}
                                                    </div>
                                                </div>

                                            </div>

                                        </div>

                                        {{-- Botones --}}
                                        <div class="flex gap-2 mt-3">
                                            <button
                                                class="flex-1 px-4 py-2 text-sm bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                                                Marcar como terminado
                                            </button>
                                            <button
                                                class="px-4 py-2 text-sm bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                                                Cancelar
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-center text-gray-500 mt-4">No hay citas para este día por ahora</p>
                            @endif

                        </div>

                </div>
            </div>

            {{-- Tabs --}}
            <div class="bg-white shadow-lg rounded-lg p-6">

                {{-- Botones de Tabs --}}
                <div class="flex border-b border-gray-200 mb-4">
                    <button
                        :class="tab === 'solicitudes' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-500'"
                        class="px-4 py-2 font-medium focus:outline-none" @click="tab = 'solicitudes'">
                        Solicitudes de Citas
                    </button>
                    <button
                        :class="tab === 'notificaciones' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-500'"
                        class="px-4 py-2 font-medium focus:outline-none" @click="tab = 'notificaciones'">
                        Notificaciones
                    </button>
                </div>

                {{-- Contenido de Tabs --}}
                <div>
                    {{-- Solicitudes de Citas --}}
                    <div x-show="tab === 'solicitudes'" class="space-y-4">
                        @foreach ($citas as $cita)
                            @if ($cita->estado === 0)
                                <div class="bg-white border-2 border-gray-300 rounded-lg shadow-sm p-3 mb-4 w-60">
                                    <div class="flex items-center space-x-4">
                                        <img class="w-16 h-16 rounded-full object-cover flex-shrink-0"
                                            src="/docs/images/blog/image-1.jpg" alt="Foto coche">
                                        <div class="flex-1">
                                            <h5 class="text-sm font-medium text-gray-900">Marca:
                                                {{ $cita->coche->marca }}</h5>
                                            <p class="text-sm text-gray-900">Modelo: {{ $cita->coche->modelo }}</p>
                                            <p class="text-sm text-gray-900">Fecha: {{ $cita->fecha }}</p>
                                        </div>
                                    </div>
                                    <div class="mt-3 w-full text-center">
                                        <button
                                            @click="abiertoDetalle = true;
                                                    marca='{{ $cita->coche->marca }}';
                                                    modelo='{{ $cita->coche->modelo }}';
                                                    fecha='{{ $cita->fecha }}';
                                                    usuario='{{ $cita->coche->usuario->nombre }}';
                                                    detalles='{{ $cita->motivo }}';
                                                    citaId='{{ $cita->id_cita }}';"
                                            class="inline-block px-6 py-1 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                                            Detalles
                                        </button>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    {{-- Notificaciones --}}
                    <div x-show="tab === 'notificaciones'" class="space-y-4">
                        <p class="text-gray-600">Aquí aparecerán las notificaciones.</p>
                        <ul class="divide-y divide-gray-200">
                            <li class="py-2">Nueva solicitud de cita de Ana Gómez</li>
                            <li class="py-2">Cita confirmada para Luis Fernández</li>
                            <li class="py-2">Cita cancelada por Carlos Ruiz</li>
                        </ul>
                    </div>
                </div>

            </div>

            {{-- Modal de detalles de cada cita --}}
            <div x-show="abiertoDetalle" x-cloak
                class="fixed inset-0 bg-black bg-opacity-20 flex items-center justify-center z-50">

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

                            <div class="flex gap-3">
                                <input type="date" name="nueva_fecha" x-model="nuevaFecha"
                                    class="flex-1 px-3 py-2 border rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">

                                <button type="submit"
                                    class="px-6 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition whitespace-nowrap">
                                    Confirmar
                                </button>
                            </div>

                        </form>

                    </div>

                </div>
            </div>


        </div>

    </div>

    {{-- FullCalendar CSS y JS --}}
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <script>
        document.addEventListener('alpine:init', () => {

            setTimeout(() => {

                let calendarEl = document.getElementById('calendar');

                if (!calendarEl) return;

                let calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    locale: 'es',
                    height: 650,
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: ''
                    },
                    dateClick: function(info) {
                        window.location.href = `/citas/por-fecha?fecha=${info.dateStr}`;
                    }
                });

                calendar.render();

            }, 100);

        });
    </script>


</x-layouts::app>
