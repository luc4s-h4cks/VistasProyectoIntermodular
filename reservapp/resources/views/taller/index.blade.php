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
        <div class="bg-background dark:bg-zinc-800 rounded-2xl shadow-md p-6">
            <h2 class="text-xl font-semibold text-text dark:text-zinc-100 border-b-2 border-secondary pb-2 mb-4">
                Calendario de citas
            </h2>

            <div class="bg-zinc-50 dark:bg-zinc-700 p-4 rounded-xl">
                {{-- Navegación mes --}}
                <div class="flex items-center justify-between mb-4">
                    <button id="prevMonth" class="px-4 py-2 bg-primary text-white rounded-lg hover:opacity-90 transition-opacity text-sm font-medium">
                        &larr; Anterior
                    </button>
                    <h4 id="mesAnio" class="font-semibold text-lg text-text dark:text-zinc-100"></h4>
                    <button id="nextMonth" class="px-4 py-2 bg-primary text-white rounded-lg hover:opacity-90 transition-opacity text-sm font-medium">
                        Siguiente &rarr;
                    </button>
                </div>

                {{-- Días de la semana --}}
                <div class="grid grid-cols-7 gap-2 text-center font-semibold text-sm text-zinc-500 dark:text-zinc-400 mb-2">
                    <div class="p-2">L</div>
                    <div class="p-2">M</div>
                    <div class="p-2">X</div>
                    <div class="p-2">J</div>
                    <div class="p-2">V</div>
                    <div class="p-2">S</div>
                    <div class="p-2">D</div>
                </div>

                {{-- Días del mes (generados por JS) --}}
                <div id="diasMes" class="grid grid-cols-7 gap-2 text-center"></div>
            </div>
        </div>

        {{-- Modal Pedir Cita --}}
        <div id="modalPedirCita" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 transition-opacity duration-300 opacity-0">
            <div id="modalContenido" class="bg-background dark:bg-zinc-800 rounded-2xl p-6 w-[90vw] max-w-md shadow-xl transform transition-all duration-300 scale-95 opacity-0 relative">
                <button onclick="cerrarModalCita()" class="absolute top-3 right-4 text-zinc-400 hover:text-text dark:hover:text-zinc-100 text-2xl font-bold">&times;</button>

                <h2 class="text-2xl font-bold text-center text-primary mb-2">Pedir Cita</h2>
                <h3 id="diaSeleccionado" class="text-lg text-center text-zinc-500 dark:text-zinc-400 mb-6"></h3>

                <form id="formPedirCita" class="space-y-4">
                    <div>
                        <flux:label>Vehículo</flux:label>
                        <flux:select>
                            <option value="">Selecciona tu vehículo</option>
                        </flux:select>
                    </div>

                    <div>
                        <flux:label>Tramo horario</flux:label>
                        <flux:select>
                            <option value="">Selecciona un tramo</option>
                            <option value="manana">Mañana</option>
                            <option value="tarde">Tarde</option>
                        </flux:select>
                    </div>

                    <div>
                        <flux:label>Motivo de la cita</flux:label>
                        <flux:textarea placeholder="Describe el motivo de tu cita..." rows="3" />
                    </div>

                    <div class="text-center pt-2">
                        <flux:button type="submit" variant="primary" class="w-full">
                            Solicitar cita
                        </flux:button>
                    </div>
                </form>
            </div>
        </div>

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
                </div>
            </div>
        </div>

    </div>

    <script>
        // ── Calendario ──
        let mesActual = new Date().getMonth();
        let anioActual = new Date().getFullYear();

        const nombresMeses = [
            'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
            'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
        ];

        function generarCalendario(mes, anio) {
            const diasMesContainer = document.getElementById('diasMes');
            const mesAnioTitulo = document.getElementById('mesAnio');

            mesAnioTitulo.textContent = `${nombresMeses[mes]} ${anio}`;
            diasMesContainer.innerHTML = '';

            const primerDia = new Date(anio, mes, 1).getDay();
            const diasEnMes = new Date(anio, mes + 1, 0).getDate();
            const ajustePrimerDia = primerDia === 0 ? 6 : primerDia - 1;

            for (let i = 0; i < ajustePrimerDia; i++) {
                const celdaVacia = document.createElement('div');
                celdaVacia.className = 'p-2';
                diasMesContainer.appendChild(celdaVacia);
            }

            const hoy = new Date();
            const diaHoy = hoy.getDate();
            const mesHoy = hoy.getMonth();
            const anioHoy = hoy.getFullYear();

            for (let dia = 1; dia <= diasEnMes; dia++) {
                const celdaDia = document.createElement('div');
                celdaDia.className = 'p-2 rounded-lg cursor-pointer transition-colors text-text dark:text-zinc-200 hover:bg-primary/10 dark:hover:bg-primary/20';
                celdaDia.textContent = dia;

                if (dia === diaHoy && mes === mesHoy && anio === anioHoy) {
                    celdaDia.className = 'p-2 rounded-lg cursor-pointer bg-primary text-white font-bold shadow-sm';
                }

                celdaDia.addEventListener('click', () => abrirModalCita(dia, mes));
                diasMesContainer.appendChild(celdaDia);
            }
        }

        // ── Modal Pedir Cita ──
        function abrirModalCita(dia, mes) {
            const modal = document.getElementById('modalPedirCita');
            const contenido = document.getElementById('modalContenido');
            document.getElementById('diaSeleccionado').textContent = `${dia} de ${nombresMeses[mes]}`;

            modal.classList.remove('hidden');
            modal.classList.add('flex');

            setTimeout(() => {
                modal.classList.remove('opacity-0');
                modal.classList.add('opacity-100');
                contenido.classList.remove('scale-95', 'opacity-0');
                contenido.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function cerrarModalCita() {
            const modal = document.getElementById('modalPedirCita');
            const contenido = document.getElementById('modalContenido');

            modal.classList.remove('opacity-100');
            modal.classList.add('opacity-0');
            contenido.classList.remove('scale-100', 'opacity-100');
            contenido.classList.add('scale-95', 'opacity-0');

            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }, 300);
        }

        document.getElementById('modalPedirCita').addEventListener('click', (e) => {
            if (e.target.id === 'modalPedirCita') cerrarModalCita();
        });

        document.getElementById('formPedirCita').addEventListener('submit', (e) => {
            e.preventDefault();
            alert('Solicitud de cita enviada correctamente!');
            cerrarModalCita();
            e.target.reset();
        });

        // ── Navegación meses ──
        document.getElementById('prevMonth').addEventListener('click', () => {
            mesActual--;
            if (mesActual < 0) { mesActual = 11; anioActual--; }
            generarCalendario(mesActual, anioActual);
        });

        document.getElementById('nextMonth').addEventListener('click', () => {
            mesActual++;
            if (mesActual > 11) { mesActual = 0; anioActual++; }
            generarCalendario(mesActual, anioActual);
        });

        // Inicializar
        generarCalendario(mesActual, anioActual);
    </script>

</x-layouts::app>
