<x-layouts::app :title="__('Factura')">

    <div class="flex justify-center mt-10" x-data="facturaApp()">

        <div class="w-full max-w-4xl bg-white rounded-xl shadow-lg p-6">

            <h1 class="text-2xl font-bold mb-6">Factura - Cita #{{ $cita->coche->usuario->nombre }}
                {{ $cita->coche->usuario->apellidos }}</h1>

            <!-- Datos del cliente y coche -->
            <div class="grid grid-cols-2 gap-6 mb-6">
                <div>
                    <p><span class="font-semibold">Cliente:</span> {{ $cita->coche->usuario->nombre }}</p>
                    <p><span class="font-semibold">Email:</span> {{ $cita->coche->usuario->email }}</p>
                </div>
                <div>
                    <p><span class="font-semibold">Coche:</span> {{ $cita->coche->marca }} {{ $cita->coche->modelo }}</p>
                    <p><span class="font-semibold">Fecha cita:</span> {{ $cita->fecha }}</p>
                </div>
                <div>
                    <p><span class="font-semibold">Coche:</span> {{ $cita->coche->marca }} {{ $cita->coche->modelo }}</p>
                    <label class="font-semibold">Motivo:</label>
                    <textarea readonly class="w-full border rounded px-3 py-2 text-sm resize-none h-20">{{ $cita->motivo }}</textarea>
                    <p><span class="font-semibold">Fecha cita:</span> {{ $cita->fecha }}</p>
                </div>

            </div>

            <!-- Tabla de items -->
            <div class="overflow-x-auto mb-6">
                <table class="min-w-full border border-gray-300 rounded-lg">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 border">Descripción</th>
                            <th class="px-4 py-2 border">Precio (€)</th>
                            <th class="px-4 py-2 border">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="(item, index) in items" :key="index">
                            <tr>
                                <td class="px-4 py-2 border">
                                    <input type="text" x-model="item.nombre" class="w-full border rounded px-2 py-1"
                                        placeholder="Servicio o pieza">
                                </td>
                                <td class="px-4 py-2 border">
                                    <input type="number" x-model="item.precio" class="w-full border rounded px-2 py-1"
                                        step="0.01" min="0">
                                </td>
                                <td class="px-4 py-2 border text-center">
                                    <button @click="eliminar(index)"
                                        class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700">
                                        Eliminar
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <div class="flex justify-end mb-6">
                <button @click="agregar()" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Añadir item
                </button>
            </div>

            <!-- Totales -->
            <div class="flex justify-end gap-6 mb-6">
                <div class="bg-gray-50 p-6 rounded-xl shadow-sm w-64">
                    <div class="flex justify-between mb-2">
                        <span>Subtotal:</span>
                        <span x-text="subtotal.toFixed(2) + ' €'"></span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span>IVA (21%):</span>
                        <span x-text="iva.toFixed(2) + ' €'"></span>
                    </div>
                    <div class="flex justify-between text-xl font-bold border-t pt-3 mt-3">
                        <span>Total:</span>
                        <span class="text-green-600" x-text="total.toFixed(2) + ' €'"></span>
                    </div>
                </div>
            </div>

            <!-- Enviar factura -->
            <div class="bg-white border rounded-xl p-6 shadow-md">

                <h2 class="text-xl font-semibold mb-4">Enviar Factura</h2>

                <div class="grid grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium mb-1">Email destinatario</label>
                        <input type="email" x-model="email" class="w-full border rounded px-3 py-2"
                            placeholder="cliente@email.com">
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Observaciones</label>
                        <input type="text" x-model="observaciones" class="w-full border rounded px-3 py-2"
                            placeholder="Gracias por confiar en nosotros">
                    </div>
                </div>

                <div class="flex justify-end gap-4">

                    <form action="{{ route('cita.enviarFactura', $cita->id_cita) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="px-5 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                            Enviar factura
                        </button>
                    </form>

                </div>

            </div>

        </div>
    </div>

    <!-- Alpine.js -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <script>
        function facturaApp() {
            return {
                items: [{
                    nombre: '',
                    precio: 0
                }],

                email: "{{ $cita->coche->usuario->email ?? '' }}",
                observaciones: '',

                agregar() {
                    this.items.push({
                        nombre: '',
                        precio: 0
                    });
                },

                eliminar(index) {
                    this.items.splice(index, 1);
                },

                get subtotal() {
                    return this.items.reduce((sum, item) => {
                        return sum + (parseFloat(item.precio) || 0);
                    }, 0);
                },

                get iva() {
                    return this.subtotal * 0.21;
                },

                get total() {
                    return this.subtotal + this.iva;
                },
            }
        }
    </script>

</x-layouts::app>
