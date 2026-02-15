<x-layouts::app :title="__('Mi Taller')">

    <div class="flex w-full">

        <x-sidebar-taller />

        <div class="flex-1 p-6">


            <div class="p-6">
                <div class="bg-white rounded-lg shadow-md p-8">

                    <form method="POST" action="{{ route('taller.guardar') }}" enctype="multipart/form-data"
                        class="space-y-8">
                        @csrf

                        <!-- ===================== -->
                        <!-- NOMBRE DEL TALLER -->
                        <!-- ===================== -->
                        <div>
                            <label class="block mb-2 text-lg font-medium text-gray-900">
                                Nombre del taller
                            </label>
                            <input type="text" name="nombre"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-3"
                                placeholder="Escribe el nombre del taller"
                                value="{{ old('nombre', $taller->nombre ?? '') }}">
                        </div>

                        <!-- ===================== -->
                        <!-- DATOS SOBRE EL TALLER -->
                        <!-- ===================== -->
                        <div>
                            <label class="block mb-2 text-lg font-medium text-gray-900">
                                Datos sobre el taller
                            </label>
                            <textarea name="descripcion" rows="4"
                                class="block w-full p-3 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Descripción, ubicación, horarios...">{{ old('descripcion', $taller->descripcion ?? '') }}</textarea>
                        </div>

                        <!-- ===================== -->
                        <!-- IMAGEN DEL TALLER -->
                        <!-- ===================== -->
                        <div>
                            <label class="block mb-2 text-lg font-medium text-gray-900">
                                Imagen del taller
                            </label>
                            <input
                                class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none"
                                type="file" name="imagen_taller">
                        </div>

                        <!-- ===================== -->
                        <!-- CALENDARIO (NO EDITABLE) -->
                        <!-- ===================== -->
                        <div>
                            <label class="block mb-2 text-lg font-medium text-gray-900">
                                Calendario
                            </label>
                            <div
                                class="flex items-center justify-center h-64 bg-gray-100 rounded-lg border border-gray-200">
                                <span class="text-gray-500">
                                    Aquí estaría el calendario (no editable)
                                </span>
                            </div>
                        </div>

                        <!-- ===================== -->
                        <!-- ESPECIALIDADES -->
                        <!-- ===================== -->
                        <div>
                            <h3 class="text-xl font-semibold mb-4">Especialidades</h3>

                            <div class="grid md:grid-cols-2 gap-8">

                                <!-- SERVICIOS -->
                                <div>
                                    <h4 class="font-medium mb-3">Servicios</h4>
                                    <div class="space-y-2">
                                        @foreach (['Frenos y suspensión', 'Mantenimiento', 'Diagnosis', 'Reparación de motor', 'Electricidad automotriz'] as $servicio)
                                            <div class="flex items-center">
                                                <input type="checkbox" name="servicios[]" value="{{ $servicio }}"
                                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500"
                                                    @checked(in_array($servicio, $taller->tipo_servicio ?? ''))>
                                                <label class="ms-2 text-sm text-gray-700">
                                                    {{ $servicio }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- TIPOS DE VEHICULO -->
                                <div>
                                    <h4 class="font-medium mb-3">Tipos de vehículo</h4>

                                    <div class="space-y-2">
                                        @foreach (['Coches', 'Motos', 'Camiones'] as $tipo)
                                            <div class="flex items-center">
                                                <input type="checkbox" name="vehiculos[]" value="{{ $tipo }}"
                                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500"
                                                     @checked(in_array($tipo, $taller->tipo_vehiculo ?? ''))>
                                                <label class="ms-2 text-sm text-gray-700">
                                                    {{ $tipo }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- ===================== -->
                        <!-- CONTACTO -->
                        <!-- ===================== -->
                        <div>
                            <h3 class="text-xl font-semibold mb-4">Contacto</h3>

                            <div class="space-y-6">

                                <!-- Imagen contacto -->
                                <div>
                                    <label class="block mb-2 text-lg font-medium text-gray-900">
                                        Imagen de contacto
                                    </label>
                                    <input
                                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50"
                                        type="file" name="imagen_contacto">
                                </div>

                                <!-- Motivos para llamar -->
                                <div>
                                    <label class="block mb-2 text-lg font-medium text-gray-900">
                                        Motivos para llamar
                                    </label>
                                    <textarea name="info_contacto" rows="3"
                                        class="block w-full p-3 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                                        placeholder="Ej: presupuestos, urgencias, citas...">{{ old('info_contacto', $taller->info_contacto ?? '') }}</textarea>
                                </div>

                                <!-- Email y telefono -->
                                <div>
                                    <label class="block mb-2 text-lg font-medium text-gray-900">
                                        teléfono
                                    </label>
                                    <input type="text" name="telefono"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-3"
                                        placeholder="123456789" value="{{ old('telefono', $taller->telefono ?? '') }}">
                                </div>
                                <div>
                                    <label class="block mb-2 text-lg font-medium text-gray-900">
                                        Email
                                    </label>
                                    <input type="text" name="email"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-3"
                                        placeholder="ejemplo@correo.com"
                                        value="{{ old('email', $taller->email ?? '') }}">
                                </div>

                            </div>
                        </div>

                        <!-- ===================== -->
                        <!-- BOTON -->
                        <!-- ===================== -->
                        <div class="text-center pt-6">
                            <button type="submit"
                                class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-lg px-8 py-3 focus:outline-none">
                                Actualizar
                            </button>
                        </div>

                    </form>

                </div>
            </div>



        </div>

    </div>

</x-layouts::app>
