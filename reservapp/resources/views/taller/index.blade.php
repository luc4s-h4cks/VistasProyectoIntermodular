<x-layouts::app :title="$taller->nombre">
    <div class="max-w-4xl mx-auto py-8 px-4 space-y-8">

        {{-- Cabecera: nombre + handle --}}
        <div class="text-center">
            <h1 class="text-3xl font-bold text-primary">{{ $taller->nombre }}</h1>
            <p class="text-zinc-500 dark:text-zinc-400">{{ $taller->handle }}</p>
        </div>

        {{-- Sección 1: Datos del taller + Imagen --}}
        <div class="bg-background dark:bg-zinc-800 rounded-2xl shadow-md p-6 flex flex-col md:flex-row gap-6">

            {{-- Datos --}}
            <div class="flex-1 space-y-4">
                <h2 class="text-xl font-semibold text-text dark:text-zinc-100 border-b-2 border-secondary pb-2">
                    Sobre el taller
                </h2>

                <p class="text-text dark:text-zinc-300 leading-relaxed">
                    {{ $taller->descripcion ?? 'Sin descripción disponible.' }}
                </p>

                {{-- Vehículos --}}
                @if ($taller->tipo_vehiculo)
                    <div>
                        <span class="text-sm font-semibold text-accent">Vehículos admitidos:</span>
                        <div class="mt-1 flex flex-wrap gap-2">
                            @foreach ($taller->tipo_vehiculo as $vehiculo)
                                <span class="px-3 py-1 text-xs font-medium rounded-full bg-primary/10 text-primary dark:bg-primary/20">
                                    {{ $vehiculo }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Servicios --}}
                @if ($taller->tipo_servicio)
                    <div>
                        <span class="text-sm font-semibold text-accent">Servicios:</span>
                        <div class="mt-1 flex flex-wrap gap-2">
                            @foreach ($taller->tipo_servicio as $servicio)
                                <span class="px-3 py-1 text-xs font-medium rounded-full bg-secondary/10 text-secondary dark:bg-secondary/20">
                                    {{ $servicio }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            {{-- Imagen principal --}}
            <div class="flex-shrink-0 flex items-start justify-center">
                @if ($taller->img_perfil)
                    <img src="{{ asset('storage/imgTalleres/' . $taller->img_perfil) }}"
                         alt="{{ $taller->nombre }}"
                         class="w-64 h-48 object-cover rounded-xl border-2 border-secondary shadow-sm">
                @else
                    <div class="w-64 h-48 bg-zinc-200 dark:bg-zinc-700 rounded-xl flex items-center justify-center text-zinc-400 dark:text-zinc-500">
                        Sin imagen
                    </div>
                @endif
            </div>
        </div>

        {{-- Sección 2: Calendario --}}
        <livewire:taller.calendario :taller="$taller" />

        {{-- Sección 3: Contacto --}}
        <div class="bg-background dark:bg-zinc-800 rounded-2xl shadow-md p-6">
            <h2 class="text-xl font-semibold text-text dark:text-zinc-100 border-b-2 border-secondary pb-2 mb-6">
                Contacto
            </h2>

            <div class="flex flex-col md:flex-row gap-6 items-start">

                {{-- Imagen secundaria --}}
                <div class="flex-shrink-0">
                    @if ($taller->img_sec)
                        <img src="{{ asset('storage/imgTalleres/' . $taller->img_sec) }}"
                             alt="Contacto {{ $taller->nombre }}"
                             class="w-40 h-40 object-cover rounded-xl border-2 border-primary shadow-sm">
                    @else
                        <div class="w-40 h-40 bg-zinc-200 dark:bg-zinc-700 rounded-xl flex items-center justify-center text-zinc-400 dark:text-zinc-500 text-sm">
                            Sin imagen
                        </div>
                    @endif
                </div>

                {{-- Info de contacto --}}
                <div class="flex-1 space-y-3">
                    @if ($taller->info_contacto)
                        <p class="text-text dark:text-zinc-300 leading-relaxed">
                            {{ $taller->info_contacto }}
                        </p>
                    @endif
                </div>

                {{-- Datos directos --}}
                <div class="flex-shrink-0 space-y-3 text-sm">
                    @if ($taller->email)
                        <div class="flex items-center gap-2 text-text dark:text-zinc-300">
                            <flux:icon name="envelope" class="w-5 h-5 text-primary" />
                            <a href="mailto:{{ $taller->email }}" class="hover:text-primary transition-colors">
                                {{ $taller->email }}
                            </a>
                        </div>
                    @endif

                    @if ($taller->telefono)
                        <div class="flex items-center gap-2 text-text dark:text-zinc-300">
                            <flux:icon name="phone" class="w-5 h-5 text-primary" />
                            <a href="tel:{{ $taller->telefono }}" class="hover:text-primary transition-colors">
                                {{ $taller->telefono }}
                            </a>
                        </div>
                    @endif

                    @if ($taller->ubicacion)
                        <div class="flex items-center gap-2 text-text dark:text-zinc-300">
                            <flux:icon name="map-pin" class="w-5 h-5 text-primary" />
                            <span>{{ $taller->ubicacion }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>

</x-layouts::app>
