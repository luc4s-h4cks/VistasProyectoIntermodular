<x-layouts::app :title="__('Suscripcion')">

    <div class="flex w-full">

        <x-sidebar-taller />

        <div class="flex-1 p-6">
            <div class="p-6">

                <!-- CARD ESTADO -->
                <div class="bg-background rounded-lg shadow-md p-8 mb-10 border border-secondary/20">

                    <h2 class="text-xl font-semibold mb-6 text-text">Estado</h2>

                    <div class="grid md:grid-cols-3 gap-6 items-center">

                        <div class="space-y-6 md:col-span-2">
                            <div class="flex justify-between">
                                <span class="text-lg font-medium text-text">Plan actual:</span>
                                <span class="text-lg text-text/70">Plan XXXXXXXX</span>
                            </div>

                            <div class="flex justify-between">
                                <span class="text-lg font-medium text-text">Tiempo restante:</span>
                                <div class="text-right">
                                    <div class="text-lg text-text">XX días</div>
                                    <div class="text-sm text-text/50">Hasta dd/mm/yyyy</div>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-center md:justify-end">
                            <button type="button"
                                class="text-white bg-primary hover:opacity-90 focus:ring-4 focus:ring-primary/30 font-medium rounded-lg text-lg px-8 py-3 focus:outline-none transition">
                                Renovar
                            </button>
                        </div>

                    </div>
                </div>

                <!-- PLANES DISPONIBLES -->
                <div class="grid gap-6 md:grid-cols-2">

                    <!-- PLAN 2 -->
                    <div class="bg-background rounded-lg shadow-md p-6 border border-secondary/20">
                        <h3 class="text-2xl font-semibold mb-4 text-text">Plan 2 (Precio)</h3>
                        <p class="mb-6 text-text/60">
                            <span class="font-semibold text-text">Ventajas plan:</span>
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit,
                            sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                            Ut enim ad minim veniam.
                        </p>
                        <button type="button"
                            class="w-full text-white bg-secondary hover:opacity-90 focus:ring-4 focus:ring-secondary/30 font-medium rounded-lg text-lg px-5 py-3 focus:outline-none transition">
                            Cambiar Plan
                        </button>
                    </div>

                    <!-- PLAN 3 -->
                    <div class="bg-background rounded-lg shadow-md p-6 border border-secondary/20">
                        <h3 class="text-2xl font-semibold mb-4 text-text">Plan 3 (Precio)</h3>
                        <p class="mb-6 text-text/60">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit,
                            sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                            Duis aute irure dolor in reprehenderit in voluptate velit esse
                            cillum dolore eu fugiat nulla pariatur.
                        </p>
                        <button type="button"
                            class="w-full text-white bg-secondary hover:opacity-90 focus:ring-4 focus:ring-secondary/30 font-medium rounded-lg text-lg px-5 py-3 focus:outline-none transition">
                            Cambiar Plan
                        </button>
                    </div>

                </div>

            </div>
        </div>

    </div>

</x-layouts::app>