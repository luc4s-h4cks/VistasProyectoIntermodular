<?php

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Taller;
use Livewire\Attributes\Validate;
use Illuminate\Support\Str;
use Illuminate\Database\QueryException;
use Illuminate\Validation\Rule;

new class extends Component {
    use WithFileUploads;

    public $taller;

    // Campos principales
    #[Validate('required|string|max:255')]
    public $nombre;

    #[Validate('required|string')]
    public $descripcion;

    #[Validate('required|string|max:255')]
    public $ubicacion;

    #[Validate('required|string|max:50')]
    public $handle;

    // Imágenes
    #[Validate('nullable|image|max:2048')]
    public $imagen_taller;
    public $imagen_taller_preview;

    #[Validate('nullable|image|max:2048')]
    public $imagen_contacto;
    public $imagen_contacto_preview;

    // Especialidades
    #[Validate('required|array|min:1')]
    public $servicios = [];

    #[Validate('required|array|min:1')]
    public $vehiculos = [];

    // Contacto
    #[Validate('nullable|string')]
    public $info_contacto;

    #[Validate('nullable|string|max:20')]
    public $telefono;

    #[Validate('nullable|email|max:255')]
    public $email;

    public function mount($taller = null)
    {
        $this->taller = $taller;

        $this->nombre = $taller->nombre ?? '';
        $this->descripcion = $taller->descripcion ?? '';
        $this->handle = $taller->handle ?? '';
        $this->servicios = $taller->tipo_servicio ?? [];
        $this->vehiculos = $taller->tipo_vehiculo ?? [];
        $this->info_contacto = $taller->info_contacto ?? '';
        $this->telefono = $taller->telefono ?? '';
        $this->email = $taller->email ?? '';
        $this->ubicacion = $taller->ubicacion ?? '';
    }

    public function updatedHandle($value)
    {
        $this->validateOnly('handle', [
            'handle' => ['required', 'string', 'max:50', Rule::unique('taller', 'handle')->ignore($this->taller?->id_taller, 'id_taller')],
        ]);
    }

    public function guardar()
    {
        $this->validate();

        try {
            $usuario = Auth::user();
            $taller = $usuario->taller;

            $datos = [
                'nombre' => $this->nombre,
                'descripcion' => $this->descripcion,
                'telefono' => $this->telefono,
                'email' => $this->email,
                'tipo_vehiculo' => $this->vehiculos,
                'tipo_servicio' => $this->servicios,
                'info_contacto' => $this->info_contacto,
                'ubicacion' => $this->ubicacion,
                'handle' => ['required', 'string', 'max:50', Rule::unique('taller', 'handle')->ignore($this->taller?->id_taller, 'id_taller')],

            ];

            if ($this->imagen_taller) {
                if ($taller && $taller->img_perfil) {
                    Storage::delete('imgTalleres/' . $taller->img_perfil);
                }
                $nombreFoto = time() . '_' . $this->imagen_taller->getClientOriginalName();
                $this->imagen_taller->storeAs('imgTalleres', $nombreFoto, 'public');
                $datos['img_perfil'] = $nombreFoto;
            }

            if ($this->imagen_contacto) {
                if ($taller && $taller->img_sec) {
                    Storage::delete('imgTalleres/' . $taller->img_sec);
                }
                $nombreFoto = time() . '_' . $this->imagen_contacto->getClientOriginalName();
                $this->imagen_contacto->storeAs('imgTalleres', $nombreFoto, 'public');
                $datos['img_sec'] = $nombreFoto;
            }
            $usuario->taller()->updateOrCreate(['id_usuario' => $usuario->id_usuario], $datos);

            session()->flash('message', 'Taller guardado correctamente');
        } catch (QueryException $e) {
            session()->flash('message', 'Ha ocurrido un error al guardar el taller');
        }
    }
};

?>

<div class="bg-white rounded-lg shadow-md p-8 space-y-8">

    @if (session()->has('message'))
        <div class="bg-green-100 text-green-800 p-3 rounded">{{ session('message') }}</div>
    @endif

    <form wire:submit="guardar()" enctype="multipart/form-data" class="space-y-8">

        <!-- NOMBRE DEL TALLER -->
        <div>
            <label class="block mb-2 text-lg font-medium text-gray-900">Nombre del taller</label>
            <input type="text" wire:model.blur.live='nombre'
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-3"
                placeholder="Escribe el nombre del taller">
            @error('nombre')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <!-- HANDLE -->
        <div>
            <label class="block mb-2 text-lg font-medium text-gray-900">Handle (URL)</label>
            <div class="flex items-center gap-2">
                <span class="text-gray-500 text-sm">talleres/</span>
                <input type="text" wire:model.blur.live='handle'
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-3"
                    placeholder="mi-taller">
            </div>
            @error('handle')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
            @if ($handle && !$errors->has('handle'))
                <span class="text-green-500 text-sm">✓ Handle disponible</span>
            @endif
        </div>

        <!-- UBICACION DEL TALLER -->
        <div>
            <label class="block mb-2 text-lg font-medium text-gray-900">Ubicación del taller</label>
            <input type="text" wire:model.blur.live='ubicacion'
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-3"
                placeholder="Escribe la ubicación del taller">
            @error('ubicacion')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <!-- DESCRIPCIÓN -->
        <div>
            <label class="block mb-2 text-lg font-medium text-gray-900">Datos sobre el taller</label>
            <textarea wire:model.blur.live='descripcion' rows="4"
                class="block w-full p-3 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                placeholder="Descripción, ubicación, horarios..."></textarea>
            @error('descripcion')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <!-- IMAGEN DEL TALLER -->
        <div>
            <label class="block mb-2 text-lg font-medium text-gray-900">Imagen del taller</label>
            <label
                class="flex flex-col items-center justify-center w-full h-48 border-2 border-dashed border-gray-300 rounded-xl cursor-pointer bg-gray-50 hover:bg-gray-100 relative overflow-hidden transition">
                @if ($imagen_taller)
                    <img src="{{ $imagen_taller->temporaryUrl() }}"
                        class="absolute inset-0 w-full h-full object-cover rounded-xl">
                    <div class="absolute inset-0 bg-black/30 flex items-center justify-center rounded-xl">
                        <span class="text-white text-sm font-medium">Cambiar imagen</span>
                    </div>
                @elseif($taller && $taller->img_perfil)
                    <img src="{{ asset('storage/imgTalleres/' . $taller->img_perfil) }}"
                        class="absolute inset-0 w-full h-full object-cover rounded-xl">
                    <div class="absolute inset-0 bg-black/30 flex items-center justify-center rounded-xl">
                        <span class="text-white text-sm font-medium">Cambiar imagen</span>
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center text-gray-400">
                        <svg class="w-10 h-10 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span class="text-sm">Haz clic para subir imagen</span>
                    </div>
                @endif
                <input type="file" wire:model.live="imagen_taller" class="hidden">
            </label>
            @error('imagen_taller')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <!-- ESPECIALIDADES -->
        <div>
            <h3 class="text-xl font-semibold mb-4">Especialidades</h3>
            <div class="grid md:grid-cols-2 gap-8">

                <!-- SERVICIOS -->
                <div>
                    <h4 class="font-medium mb-3">Servicios</h4>
                    <div class="space-y-2">
                        @foreach (['Frenos y suspensión', 'Mantenimiento', 'Diagnosis', 'Reparación de motor', 'Electricidad automotriz'] as $servicio)
                            <div class="flex items-center">
                                <input type="checkbox" wire:model.blur.live='servicios' value="{{ $servicio }}"
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                <label class="ms-2 text-sm text-gray-700">{{ $servicio }}</label>
                            </div>
                        @endforeach
                    </div>
                    @error('servicios')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- VEHÍCULOS -->
                <div>
                    <h4 class="font-medium mb-3">Tipos de vehículo</h4>
                    <div class="space-y-2">
                        @foreach (['Coches', 'Motos', 'Camiones'] as $tipo)
                            <div class="flex items-center">
                                <input type="checkbox" wire:model.blur.live='vehiculos' value="{{ $tipo }}"
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                <label class="ms-2 text-sm text-gray-700">{{ $tipo }}</label>
                            </div>
                        @endforeach
                    </div>
                    @error('vehiculos')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

            </div>
        </div>

        <!-- CONTACTO -->
        <div>
            <h3 class="text-xl font-semibold mb-4">Contacto</h3>
            <div class="space-y-6">

                <!-- IMAGEN CONTACTO -->
                <div>
                    <label class="block mb-2 text-lg font-medium text-gray-900">Imagen de contacto</label>
                    <label
                        class="flex flex-col items-center justify-center w-full h-48 border-2 border-dashed border-gray-300 rounded-xl cursor-pointer bg-gray-50 hover:bg-gray-100 relative overflow-hidden transition">
                        @if ($imagen_contacto)
                            <img src="{{ $imagen_contacto->temporaryUrl() }}"
                                class="absolute inset-0 w-full h-full object-cover rounded-xl">
                            <div class="absolute inset-0 bg-black/30 flex items-center justify-center rounded-xl">
                                <span class="text-white text-sm font-medium">Cambiar imagen</span>
                            </div>
                        @elseif($taller && $taller->img_sec)
                            <img src="{{ asset('storage/imgTalleres/' . $taller->img_sec) }}"
                                class="absolute inset-0 w-full h-full object-cover rounded-xl">
                            <div class="absolute inset-0 bg-black/30 flex items-center justify-center rounded-xl">
                                <span class="text-white text-sm font-medium">Cambiar imagen</span>
                            </div>
                        @else
                            <div class="flex flex-col items-center justify-center text-gray-400">
                                <svg class="w-10 h-10 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span class="text-sm">Haz clic para subir imagen</span>
                            </div>
                        @endif
                        <input type="file" wire:model.live="imagen_contacto" class="hidden">
                    </label>
                    @error('imagen_contacto')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Info contacto -->
                <div>
                    <label class="block mb-2 text-lg font-medium text-gray-900">Motivos para llamar</label>
                    <textarea wire:model.blur.live='info_contacto' rows="3"
                        class="block w-full p-3 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Ej: presupuestos, urgencias, citas..."></textarea>
                    @error('info_contacto')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Teléfono -->
                <div>
                    <label class="block mb-2 text-lg font-medium text-gray-900">Teléfono</label>
                    <input type="text" wire:model.blur.live='telefono'
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-3"
                        placeholder="123456789">
                    @error('telefono')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label class="block mb-2 text-lg font-medium text-gray-900">Email</label>
                    <input type="text" wire:model.blur.live='email'
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-3"
                        placeholder="ejemplo@correo.com">
                    @error('email')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
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
