{{-- resources/views/components/admin/sidebar-admin.blade.php --}}
<div class="flex w-full min-h-screen">

    <!-- Sidebar -->
    <aside
        class="w-64 shrink-0 border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900 min-h-screen px-3 py-4">

        <div class="mb-6 px-2 text-sm font-semibold text-zinc-500 uppercase tracking-wider">
            Administración
        </div>

        <ul class="space-y-1">
            <li>
                <a href="{{ route('admin.usuarios') }}"
                    class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition
                    {{ request()->routeIs('admin.usuarios') ? 'bg-blue-100 text-blue-700 font-medium dark:bg-blue-900 dark:text-blue-300' : 'text-zinc-700 hover:bg-zinc-100 dark:text-zinc-300 dark:hover:bg-zinc-800' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M17 20h5v-2a4 4 0 00-5-4.93M9 20H4v-2a4 4 0 015-4.93M15 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    Gestión de usuarios
                </a>
            </li>
        </ul>

    </aside>

    <!-- Contenido -->
    <main class="flex-1 p-6">
        {{ $slot }}
    </main>

</div>
