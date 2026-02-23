<!-- Botón móvil -->
<button data-drawer-target="app-sidebar" data-drawer-toggle="app-sidebar" aria-controls="app-sidebar" type="button"
    class="inline-flex items-center p-2 mt-2 ms-3 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">
    <span class="sr-only">Abrir menú</span>
    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd"
            d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"
            clip-rule="evenodd" />
    </svg>
</button>

<!-- Sidebar -->
<aside id="app-sidebar"
    class="fixed top-16 left-0 z-40 w-64 h-[calc(100vh-4rem)] transition-transform -translate-x-full sm:translate-x-0"
    aria-label="Sidebar">

    <div class="h-full px-3 py-4 overflow-y-auto bg-background text-text shadow-lg">

        <h2 class="text-lg font-bold mb-6 px-2">Menú</h2>

        <ul class="space-y-2 font-medium">

            <!-- Suscripción -->
            <li>
                <a href="{{ route('suscripcion') }}"
                    class="flex items-center p-2 rounded-lg hover:bg-gray-100 transition group">
                    <span class="text-lg">💳</span>
                    <span class="ms-3">Suscripción</span>
                </a>
            </li>

            <!-- Página Taller -->
            <li>
                <a href="{{ route('mi-taller') }}"
                    class="flex items-center p-2 rounded-lg hover:bg-gray-100 transition group">
                    <span class="text-lg">🛠</span>
                    <span class="ms-3">Página taller</span>
                </a>
            </li>

            <!-- Gestión Citas -->
            <li>
                <a href="{{ route('gestion-citas') }}"
                    class="flex items-center p-2 rounded-lg hover:bg-gray-100 transition group">
                    <span class="text-lg">📅</span>
                    <span class="ms-3">Gestión citas</span>
                </a>
            </li>

        </ul>

    </div>
</aside>

<!-- Contenido principal -->
<div class="p-4 sm:ml-64">
    {{ $slot ?? '' }}
</div>
