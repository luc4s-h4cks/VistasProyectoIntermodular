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
                        <!-- DESCRIPCIÓN -->
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

                            @if ($taller && $taller->img_perfil)
                                <div class="mb-4">
                                    <img src="{{ asset('storage/imgTalleres/' . $taller->img_perfil) }}"
                                        class="w-48 h-32 object-cover rounded-lg shadow">
                                </div>
                            @endif

                            <input type="file" name="imagen_taller"
                                class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50">
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
                                                    @checked(in_array($servicio, $taller->tipo_servicio ?? []))>
                                                <label class="ms-2 text-sm text-gray-700">
                                                    {{ $servicio }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- VEHÍCULOS -->
                                <div>
                                    <h4 class="font-medium mb-3">Tipos de vehículo</h4>

                                    <div class="space-y-2">
                                        @foreach (['Coches', 'Motos', 'Camiones'] as $tipo)
                                            <div class="flex items-center">
                                                <input type="checkbox" name="vehiculos[]" value="{{ $tipo }}"
                                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500"
                                                    @checked(in_array($tipo, $taller->tipo_vehiculo ?? []))>
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

                                    @if ($taller && $taller->img_sec)
                                        <div class="mb-4">
                                            <img src="{{ asset('storage/imgTalleres/' . $taller->img_sec) }}"
                                                class="w-48 h-32 object-cover rounded-lg shadow">
                                        </div>
                                    @endif

                                    <input type="file" name="imagen_contacto"
                                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50">
                                </div>

                                <!-- Info contacto -->
                                <div>
                                    <label class="block mb-2 text-lg font-medium text-gray-900">
                                        Motivos para llamar
                                    </label>
                                    <textarea name="info_contacto" rows="3"
                                        class="block w-full p-3 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                                        placeholder="Ej: presupuestos, urgencias, citas...">{{ old('info_contacto', $taller->info_contacto ?? '') }}</textarea>
                                </div>

                                <!-- Teléfono -->
                                <div>
                                    <label class="block mb-2 text-lg font-medium text-gray-900">
                                        Teléfono
                                    </label>
                                    <input type="text" name="telefono"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-3"
                                        placeholder="123456789" value="{{ old('telefono', $taller->telefono ?? '') }}">
                                </div>

                                <!-- Email -->
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

                        <!-- BOTÓN -->
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
