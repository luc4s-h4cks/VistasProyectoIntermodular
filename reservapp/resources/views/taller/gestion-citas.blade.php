<x-layouts::app :title="__('Gestión de Citas')">

    <div class="flex w-full">

        {{-- Sidebar --}}
        <x-sidebar-taller />

        {{-- Contenido principal --}}
        <div class="flex-1 p-6">

            {{-- Calendario --}}
            <div class="bg-white shadow-lg rounded-lg p-6 mb-6">
                <h2 class="text-2xl font-bold mb-6">Calendario de Citas</h2>
                <div id="calendar"></div>
            </div>

            <div x-data="{ abierto: false, fechaSeleccionada: '' }"
     id="modal-dia"
     x-show="abierto"
     x-cloak
     class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">

    <div class="bg-white rounded-lg shadow-lg w-96 p-6 relative">

        {{-- Botón cerrar --}}
        <button @click="abierto = false"
                class="absolute top-2 right-2 text-gray-500 hover:text-gray-700">
            &times;
        </button>

        {{-- Contenido personalizado --}}
        <h2 class="text-xl font-bold mb-4">Acciones para el día</h2>

        <p class="mb-4">Fecha seleccionada: <span x-text="fechaSeleccionada"></span></p>

        {{-- Aquí puedes poner botones, formularios, etc --}}
        <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 mb-2 w-full">
            Crear nueva cita
        </button>

        <button class="bg-gray-200 text-gray-800 px-4 py-2 rounded hover:bg-gray-300 w-full">
            Ver citas existentes
        </button>

    </div>
</div>


            {{-- Zona de Tabs --}}
            <div x-data="{ tab: 'solicitudes' }" class="bg-white shadow-lg rounded-lg p-6">

                {{-- Botones de Tabs --}}
                <div class="flex border-b border-gray-200 mb-4">
                    <button :class="tab === 'solicitudes' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-500'"
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
                        <p class="text-gray-600">Aquí aparecerán las solicitudes de citas.</p>
                        {{-- Ejemplo de lista --}}
                        <ul class="divide-y divide-gray-200">
                            <li class="py-2">Cita de Juan Pérez - 10:00 AM</li>
                            <li class="py-2">Cita de María López - 11:30 AM</li>
                            <li class="py-2">Cita de Pedro Martínez - 2:00 PM</li>
                        </ul>
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

        </div>

    </div>

    {{-- FullCalendar CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">
    {{-- FullCalendar JS --}}
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
    {{-- Alpine.js --}}
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            let calendarEl = document.getElementById('calendar');

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
                    alert("Has seleccionado el día: " + info.dateStr);
                }
            });

            calendar.render();
        });
    </script>

</x-layouts::app>



