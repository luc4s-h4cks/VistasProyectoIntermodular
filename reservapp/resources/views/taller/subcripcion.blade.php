<x-layouts::app :title="__('Suscripcion')">

    <div class="flex w-full">

        <x-sidebar-taller />

        <div class="flex-1 p-6">
            <div class="p-6">

                @if (session('success'))
                    <div
                        class="mb-6 p-4 bg-green-100 border border-green-300 text-green-800 rounded-lg flex items-center gap-3">
                        <span class="text-xl">✅</span>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                <!-- CARD ESTADO -->
                <div class="bg-background rounded-2xl shadow-md p-8 mb-10 border border-secondary/20">

                    <div class="flex items-center gap-3 mb-8">
                        <span class="text-2xl">💳</span>
                        <h2 class="text-xl font-semibold text-text">Estado de tu Suscripción</h2>
                    </div>

                    <div class="grid md:grid-cols-3 gap-8 items-center">

                        <div class="md:col-span-2 divide-y divide-secondary/10">

                            <div class="flex justify-between items-center py-4">
                                <span class="text-base font-medium text-text/70">Plan actual</span>
                                @if ($suscripcionActiva)
                                    <span
                                        class="text-base font-semibold text-text bg-primary/10 text-primary px-3 py-1 rounded-full">
                                        {{ $planActual->nombre }}
                                    </span>
                                @else
                                    <span class="text-base font-semibold text-red-500 bg-red-50 px-3 py-1 rounded-full">
                                        Sin suscripción activa
                                    </span>
                                @endif
                            </div>

                            <div class="flex justify-between items-center py-4">
                                <span class="text-base font-medium text-text/70">Tiempo restante</span>
                                @if ($suscripcionActiva)
                                    <div class="text-right">
                                        <div class="text-base font-semibold text-text">{{ $diasRestantes }} días</div>
                                        <div class="text-sm text-text/40">
                                            Vence el
                                            {{ \Carbon\Carbon::parse($taller->fecha_fin_suscripcion)->format('d/m/Y') }}
                                        </div>
                                    </div>
                                @else
                                    <span class="text-base text-text/30">—</span>
                                @endif
                            </div>

                            @if ($suscripcionActiva)
                                <div class="flex justify-between items-center py-4">
                                    <span class="text-base font-medium text-text/70">Precio</span>
                                    <span class="text-base font-semibold text-text">
                                        ${{ number_format($planActual->precio, 2) }}/mes
                                    </span>
                                </div>
                            @endif

                        </div>

                        @if ($suscripcionActiva)
                            <div class="flex flex-col items-center md:items-end gap-3">
                                <form action="{{ route('suscripcion.contratar') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="id_plan" value="{{ $planActual->id_estado }}">
                                    <button type="submit"
                                        class="text-white bg-primary hover:opacity-90 focus:ring-4 focus:ring-primary/30 font-medium rounded-xl text-base px-10 py-3 focus:outline-none transition shadow-sm">
                                        🔄 Renovar plan
                                    </button>
                                </form>
                                <p class="text-xs text-text/40 text-center">Extiende 30 días desde hoy</p>
                            </div>
                        @endif

                    </div>
                </div>

                <!-- PLANES DISPONIBLES -->
                <div class="flex items-center gap-3 mb-6">
                    <span class="text-2xl">📦</span>
                    <h2 class="text-xl font-semibold text-text">
                        {{ $suscripcionActiva ? 'Cambiar de Plan' : 'Elige un Plan para Comenzar' }}
                    </h2>
                </div>

                <div class="grid gap-6 md:grid-cols-{{ count($planes) > 2 ? '3' : '2' }}">

                    @foreach ($planes as $plan)
                        @if (!$suscripcionActiva || $planActual->id_estado !== $plan->id_estado)
                            <div
                                class="bg-background rounded-2xl shadow-md border border-secondary/20 overflow-hidden flex flex-col">

                                <!-- Cabecera de la card -->
                                <div class="bg-secondary/5 px-6 pt-6 pb-4 border-b border-secondary/10">
                                    <h3 class="text-xl font-bold text-text mb-1">{{ $plan->nombre }}</h3>
                                    <div class="flex items-end gap-1">
                                        <span
                                            class="text-4xl font-extrabold text-primary">${{ number_format($plan->precio, 2) }}</span>
                                        <span class="text-sm text-text/40 mb-1">/mes</span>
                                    </div>
                                </div>

                                <!-- Cuerpo de la card -->
                                <div class="px-6 py-5 flex-1 flex flex-col justify-between gap-6">
                                    <p class="text-text/60 text-sm leading-relaxed">
                                        {{ $plan->descripcion }}
                                    </p>

                                    <form action="{{ route('suscripcion.contratar') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="id_plan" value="{{ $plan->id_estado }}">
                                        <button type="submit"
                                            class="w-full text-white bg-secondary hover:opacity-90 focus:ring-4 focus:ring-secondary/30 font-medium rounded-xl text-base px-5 py-3 focus:outline-none transition shadow-sm">
                                            {{ $suscripcionActiva ? '⬆️ Cambiar a este Plan' : '🚀 Contratar' }}
                                        </button>
                                    </form>
                                </div>

                            </div>
                        @endif
                    @endforeach

                </div>

            </div>
        </div>

    </div>

</x-layouts::app>
