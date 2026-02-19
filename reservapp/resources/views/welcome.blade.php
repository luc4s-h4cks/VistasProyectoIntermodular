<x-layouts::app :title="__('Inicio')" >
    <div class="flex h-full w-full flex-1 flex-col rounded-xl">
           <!-- HERO SECTION -->
    <section class="relative bg-gradient-to-r from-[var(--color-accent)] to-[var(--color-secondary)] text-white py-20 md:py-32 overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-10 left-10 w-64 h-64 bg-white rounded-full blur-3xl"></div>
            <div class="absolute bottom-10 right-10 w-96 h-96 bg-white rounded-full blur-3xl"></div>
        </div>

        <div class="container mx-auto px-4 md:px-6 relative z-10">
            <div class="max-w-3xl mx-auto text-center">
                <h1 class="text-4xl md:text-6xl font-bold mb-6 leading-tight">
                    Encuentra el Taller Perfecto para tu Vehículo
                </h1>
                <p class="text-lg md:text-xl mb-8 text-white/90">
                    Conectamos a los mejores mecánicos con miles de clientes. Agenda tu cita de forma rápida y segura.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('dashboard') }}" class="bg-white text-[var(--color-primary)] px-8 py-4 rounded-full font-bold text-lg hover:bg-gray-50 transition-colors shadow-lg">
                        Buscar Talleres
                    </a>
                    <!-- POR DEFINIR LA URL -->
                    <a href="{{ route('taller.index') }}" class="bg-transparent border-2 border-white text-white px-8 py-4 rounded-full font-bold text-lg hover:bg-white/10 transition-colors">
                        ¿Eres taller?
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- SERVICIOS -->
    <section class="py-16 md:py-24 bg-gray-50 dark:bg-zinc-900">
        <div class="container mx-auto px-4 md:px-6">
            <h2 class="text-3xl md:text-4xl font-bold text-center mb-4 dark:text-white">¿Qué ofrecemos?</h2>
            <p class="text-center text-gray-600 dark:text-zinc-400 mb-12 max-w-2xl mx-auto">
                Una plataforma completa para gestionar todas tus necesidades de mantenimiento vehicular
            </p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Servicio 1 -->
                <div class="bg-white dark:bg-zinc-800 rounded-xl p-8 shadow-lg hover:shadow-xl transition-shadow">
                    <div class="w-16 h-16 bg-[var(--color-primary)] rounded-full flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3 dark:text-white">Busca y Compara</h3>
                    <p class="text-gray-600 dark:text-zinc-400">
                        Encuentra talleres cerca de ti, compara precios, servicios y valoraciones de otros usuarios.
                    </p>
                </div>

                <!-- Servicio 2 -->
                <div class="bg-white dark:bg-zinc-800 rounded-xl p-8 shadow-lg hover:shadow-xl transition-shadow">
                    <div class="w-16 h-16 bg-[var(--color-primary)] rounded-full flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3 dark:text-white">Agenda Online</h3>
                    <p class="text-gray-600 dark:text-zinc-400">
                        Reserva tu cita en segundos. Elige el día y hora que mejor te convenga sin llamadas telefónicas.
                    </p>
                </div>

                <!-- Servicio 3 -->
                <div class="bg-white dark:bg-zinc-800 rounded-xl p-8 shadow-lg hover:shadow-xl transition-shadow">
                    <div class="w-16 h-16 bg-[var(--color-primary)] rounded-full flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3 dark:text-white">Talleres Verificados</h3>
                    <p class="text-gray-600 dark:text-zinc-400">
                        Todos nuestros talleres están verificados y cuentan con valoraciones reales de clientes.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- CATEGORÍAS -->
    <section class="py-16 md:py-24 dark:bg-zinc-800">
        <div class="container mx-auto px-4 md:px-6">
            <h2 class="text-3xl md:text-4xl font-bold text-center mb-12 dark:text-white">Servicios Disponibles</h2>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
                <div class="bg-gradient-to-br from-[var(--color-secondary)] to-[var(--color-accent)] rounded-lg p-6 text-white text-center hover:scale-105 transition-transform cursor-pointer">
                    <div class="text-4xl mb-3">🔧</div>
                    <h4 class="font-bold">Mantenimiento</h4>
                </div>
                <div class="bg-gradient-to-br from-[var(--color-accent)] to-[var(--color-primary)] rounded-lg p-6 text-white text-center hover:scale-105 transition-transform cursor-pointer">
                    <div class="text-4xl mb-3">⚙️</div>
                    <h4 class="font-bold">Reparación Motor</h4>
                </div>
                <div class="bg-gradient-to-br from-[var(--color-secondary)] to-[var(--color-accent)] rounded-lg p-6 text-white text-center hover:scale-105 transition-transform cursor-pointer">
                    <div class="text-4xl mb-3">🛞</div>
                    <h4 class="font-bold">Neumáticos</h4>
                </div>
                <div class="bg-gradient-to-br from-[var(--color-accent)] to-[var(--color-primary)] rounded-lg p-6 text-white text-center hover:scale-105 transition-transform cursor-pointer">
                    <div class="text-4xl mb-3">🔌</div>
                    <h4 class="font-bold">Electricidad</h4>
                </div>
                <div class="bg-gradient-to-br from-[var(--color-secondary)] to-[var(--color-accent)] rounded-lg p-6 text-white text-center hover:scale-105 transition-transform cursor-pointer">
                    <div class="text-4xl mb-3">🚗</div>
                    <h4 class="font-bold">Diagnosis</h4>
                </div>
                <div class="bg-gradient-to-br from-[var(--color-accent)] to-[var(--color-primary)] rounded-lg p-6 text-white text-center hover:scale-105 transition-transform cursor-pointer">
                    <div class="text-4xl mb-3">🛡️</div>
                    <h4 class="font-bold">Frenos</h4>
                </div>
                <div class="bg-gradient-to-br from-[var(--color-secondary)] to-[var(--color-accent)] rounded-lg p-6 text-white text-center hover:scale-105 transition-transform cursor-pointer">
                    <div class="text-4xl mb-3">💨</div>
                    <h4 class="font-bold">Suspensión</h4>
                </div>
                <div class="bg-gradient-to-br from-[var(--color-accent)] to-[var(--color-primary)] rounded-lg p-6 text-white text-center hover:scale-105 transition-transform cursor-pointer">
                    <div class="text-4xl mb-3">🎨</div>
                    <h4 class="font-bold">Chapa y Pintura</h4>
                </div>
            </div>
        </div>
    </section>

    <!-- ESTADÍSTICAS -->
    <section class="bg-gradient-to-r from-gray-900 to-gray-800 dark:from-zinc-950 dark:to-zinc-900 text-white py-16 md:py-20">
        <div class="container mx-auto px-4 md:px-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                <div>
                    <div class="text-4xl md:text-5xl font-bold text-[var(--color-accent)] mb-2">500+</div>
                    <div class="text-gray-300">Talleres Verificados</div>
                </div>
                <div>
                    <div class="text-4xl md:text-5xl font-bold text-[var(--color-accent)] mb-2">10k+</div>
                    <div class="text-gray-300">Clientes Satisfechos</div>
                </div>
                <div>
                    <div class="text-4xl md:text-5xl font-bold text-[var(--color-accent)] mb-2">50k+</div>
                    <div class="text-gray-300">Citas Realizadas</div>
                </div>
                <div>
                    <div class="text-4xl md:text-5xl font-bold text-[var(--color-accent)] mb-2">4.8★</div>
                    <div class="text-gray-300">Valoración Media</div>
                </div>
            </div>
        </div>
    </section>

    <!-- TESTIMONIOS -->
    <section class="py-16 md:py-24 bg-gray-50 dark:bg-zinc-900">
        <div class="container mx-auto px-4 md:px-6">
            <h2 class="text-3xl md:text-4xl font-bold text-center mb-12 dark:text-white">Lo que dicen nuestros usuarios</h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white dark:bg-zinc-800 rounded-xl p-6 shadow-lg">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-[var(--color-primary)] rounded-full flex items-center justify-center text-white font-bold mr-3">
                            JM
                        </div>
                        <div>
                            <div class="font-bold dark:text-white">Juan Martínez</div>
                            <div class="text-yellow-500">★★★★★</div>
                        </div>
                    </div>
                    <p class="text-gray-600 dark:text-zinc-400">
                        "Increíble plataforma. Encontré un taller cerca de casa con excelentes valoraciones. La cita fue súper fácil de agendar."
                    </p>
                </div>

                <div class="bg-white dark:bg-zinc-800 rounded-xl p-6 shadow-lg">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-[var(--color-primary)] rounded-full flex items-center justify-center text-white font-bold mr-3">
                            MG
                        </div>
                        <div>
                            <div class="font-bold dark:text-white">María García</div>
                            <div class="text-yellow-500">★★★★★</div>
                        </div>
                    </div>
                    <p class="text-gray-600 dark:text-zinc-400">
                        "Como dueña de taller, esta plataforma me ha ayudado a conseguir más clientes y organizar mejor mis citas."
                    </p>
                </div>

                <div class="bg-white dark:bg-zinc-800 rounded-xl p-6 shadow-lg">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-[var(--color-primary)] rounded-full flex items-center justify-center text-white font-bold mr-3">
                            CR
                        </div>
                        <div>
                            <div class="font-bold dark:text-white">Carlos Rodríguez</div>
                            <div class="text-yellow-500">★★★★★</div>
                        </div>
                    </div>
                    <p class="text-gray-600 dark:text-zinc-400">
                        "Muy recomendable. Pude comparar varios talleres y elegir el que mejor se adaptaba a mis necesidades y presupuesto."
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA FINAL -->
    <section class="bg-gradient-to-r from-[var(--color-secondary)] to-[var(--color-accent)] text-white py-16 md:py-20">
        <div class="container mx-auto px-4 md:px-6 text-center">
            <h2 class="text-3xl md:text-5xl font-bold mb-6">¿Listo para empezar?</h2>
            <p class="text-lg md:text-xl mb-8 text-white/90 max-w-2xl mx-auto">
                Únete a miles de usuarios que ya confían en nosotros para el cuidado de sus vehículos
            </p>
            <button class="bg-white text-[var(--color-primary)] px-10 py-4 rounded-full font-bold text-lg hover:bg-gray-50 transition-colors shadow-xl">
                Encuentra tu Taller Ahora
            </button>
        </div>
    </section>
    </div>
</x-layouts::app>
