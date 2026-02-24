<?php

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Taller;
use Livewire\Attributes\Validate;
use Illuminate\Support\Str;
use Illuminate\Database\QueryException;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

new class extends Component {
    use WithFileUploads;

    public $taller;

    // Campos principales
    #[Validate('required|string|max:255')]
    public $nombre;

    #[Validate('required|string|max:255')]
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
    #[Validate('nullable|string|max:255')]
    public $info_contacto;

    #[Validate('nullable|string|max:12')]
    public $telefono;

    #[Validate('nullable|email|max:50')]
    public $email;

    protected array $messages = [
        'nombre.required' => 'El nombre del taller es obligatorio.',
        'nombre.max' => 'El nombre del taller no puede superar los 255 caracteres.',
        'descripcion.required' => 'La descripción del taller es obligatoria.',
        'descripcion.max' => 'La descripción del taller no puede superar los 255 caracteres.',
        'ubicacion.required' => 'La ubicación del taller es obligatoria.',
        'handle.required' => 'El handle del taller es obligatorio.',
        'handle.max' => 'El handle del taller no puede superar los 50 caracteres.',
        'handle.unique' => 'Este handle ya está en uso. Por favor, elige otro.',
        'servicios.required' => 'Debes seleccionar al menos un servicio.',
        'vehiculos.required' => 'Debes seleccionar al menos un tipo de vehículo.',
        'info_contacto.max' => 'La información de contacto no puede superar los 255 caracteres.',
        'telefono.max' => 'El teléfono no puede superar los 12 caracteres.',
        'email.email' => 'El email debe ser una dirección de correo válida.',
        'email.max' => 'El email no puede superar los 50 caracteres.',
        'imagen_taller.image' => 'La imagen del taller debe ser un archivo de imagen.',
        'imagen_taller.max' => 'La imagen del taller no puede superar los 2MB.',
        'imagen_contacto.image' => 'La imagen de contacto debe ser un archivo de imagen.',
        'imagen_contacto.max' => 'La imagen de contacto no puede superar los 2MB.',
    ];

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
                'handle' => $this->handle,
            ];

            if ($this->imagen_taller) {
                if ($taller && $taller->img_perfil) {
                    Storage::disk('public')->delete('imgTalleres/' . $taller->img_perfil);
                }
                $nombreFoto = time() . '_' . $this->imagen_taller->getClientOriginalName();
                $this->imagen_taller->storeAs('imgTalleres', $nombreFoto, 'public');
                $datos['img_perfil'] = $nombreFoto;
            }

            if ($this->imagen_contacto) {
                if ($taller && $taller->img_sec) {
                    Storage::disk('public')->delete('imgTalleres/' . $taller->img_sec);
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

<div class="bg-background rounded-lg shadow-md p-8 space-y-8">

    @if (session()->has('message'))
        <div class="bg-green-100 text-green-800 p-3 rounded">{{ session('message') }}</div>
    @endif

    <form wire:submit="guardar()" enctype="multipart/form-data" class="space-y-8">

        <!-- NOMBRE DEL TALLER -->
        <div>
            <label class="block mb-2 text-lg font-medium text-text">Nombre del taller</label>
            <input type="text" wire:model.blur.live='nombre'
                class="bg-secondary/5 border border-text/20 text-text text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-3"
                placeholder="Escribe el nombre del taller">
            @error('nombre') <span class="text-accent text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- HANDLE -->
        <div>
            <label class="block mb-2 text-lg font-medium text-text">Handle (URL)</label>
            <div class="flex items-center gap-2">
                <span class="text-text/50 text-sm">buscador/</span>
                <input type="text" wire:model.blur.live='handle'
                    class="bg-secondary/5 border border-text/20 text-text text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-3"
                    placeholder="mi-taller">
            </div>
            @error('handle') <span class="text-accent text-sm">{{ $message }}</span> @enderror
            @if ($handle && !$errors->has('handle'))
                <span class="text-green-500 text-sm">✓ Handle disponible</span>
            @endif
        </div>

        <!-- UBICACION -->
        <div>
            <label class="block mb-2 text-lg font-medium text-text">Ubicación del taller</label>
            <input type="text" wire:model.blur.live='ubicacion'
                class="bg-secondary/5 border border-text/20 text-text text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-3"
                placeholder="Escribe la ubicación del taller">
            @error('ubicacion') <span class="text-accent text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- DESCRIPCIÓN -->
        <div>
            <label class="block mb-2 text-lg font-medium text-text">Datos sobre el taller</label>
            <textarea wire:model.blur.live='descripcion' rows="4"
                class="block w-full p-3 text-sm text-text bg-secondary/5 rounded-lg border border-text/20 focus:ring-primary focus:border-primary"
                placeholder="Descripción, ubicación, horarios..."></textarea>
            @error('descripcion') <span class="text-accent text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- IMAGEN DEL TALLER -->
        <div>
            <label class="block mb-2 text-lg font-medium text-text">Imagen del taller</label>
            <label class="flex flex-col items-center justify-center w-full h-48 border-2 border-dashed border-secondary/30 rounded-xl cursor-pointer bg-secondary/5 hover:bg-secondary/10 relative overflow-hidden transition">
                @if ($imagen_taller)
                    <img src="{{ $imagen_taller->temporaryUrl() }}" class="absolute inset-0 w-full h-full object-cover rounded-xl">
                    <div class="absolute inset-0 bg-black/30 flex items-center justify-center rounded-xl">
                        <span class="text-white text-sm font-medium">Cambiar imagen</span>
                    </div>
                @elseif($taller && $taller->img_perfil)
                    <img src="{{ asset('storage/imgTalleres/' . $taller->img_perfil) }}" class="absolute inset-0 w-full h-full object-cover rounded-xl">
                    <div class="absolute inset-0 bg-black/30 flex items-center justify-center rounded-xl">
                        <span class="text-white text-sm font-medium">Cambiar imagen</span>
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center text-text/40">
                        <svg class="w-10 h-10 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span class="text-sm">Haz clic para subir imagen</span>
                    </div>
                @endif
                <input type="file" wire:model.live="imagen_taller" class="hidden">
            </label>
            @error('imagen_taller') <span class="text-accent text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- ESPECIALIDADES -->
        <div>
            <h3 class="text-xl font-semibold mb-4 text-text">Especialidades</h3>
            <div class="grid md:grid-cols-2 gap-8">

                <div>
                    <h4 class="font-medium mb-3 text-text">Servicios</h4>
                    <div class="space-y-2">
                        @foreach (['Frenos y suspensión', 'Mantenimiento', 'Diagnosis', 'Reparación de motor', 'Electricidad automotriz'] as $servicio)
                            <div class="flex items-center">
                                <input type="checkbox" wire:model.blur.live='servicios' value="{{ $servicio }}"
                                    class="w-4 h-4 text-primary bg-secondary/5 border-text/20 rounded focus:ring-primary">
                                <label class="ms-2 text-sm text-text/70">{{ $servicio }}</label>
                            </div>
                        @endforeach
                    </div>
                    @error('servicios') <span class="text-accent text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <h4 class="font-medium mb-3 text-text">Tipos de vehículo</h4>
                    <div class="space-y-2">
                        @foreach (['Coches', 'Motos', 'Camiones'] as $tipo)
                            <div class="flex items-center">
                                <input type="checkbox" wire:model.blur.live='vehiculos' value="{{ $tipo }}"
                                    class="w-4 h-4 text-primary bg-secondary/5 border-text/20 rounded focus:ring-primary">
                                <label class="ms-2 text-sm text-text/70">{{ $tipo }}</label>
                            </div>
                        @endforeach
                    </div>
                    @error('vehiculos') <span class="text-accent text-sm">{{ $message }}</span> @enderror
                </div>

            </div>
        </div>

        <!-- CONTACTO -->
        <div>
            <h3 class="text-xl font-semibold mb-4 text-text">Contacto</h3>
            <div class="space-y-6">

                <!-- IMAGEN CONTACTO -->
                <div>
                    <label class="block mb-2 text-lg font-medium text-text">Imagen de contacto</label>
                    <label class="flex flex-col items-center justify-center w-full h-48 border-2 border-dashed border-secondary/30 rounded-xl cursor-pointer bg-secondary/5 hover:bg-secondary/10 relative overflow-hidden transition">
                        @if ($imagen_contacto)
                            <img src="{{ $imagen_contacto->temporaryUrl() }}" class="absolute inset-0 w-full h-full object-cover rounded-xl">
                            <div class="absolute inset-0 bg-black/30 flex items-center justify-center rounded-xl">
                                <span class="text-white text-sm font-medium">Cambiar imagen</span>
                            </div>
                        @elseif($taller && $taller->img_sec)
                            <img src="{{ asset('storage/imgTalleres/' . $taller->img_sec) }}" class="absolute inset-0 w-full h-full object-cover rounded-xl">
                            <div class="absolute inset-0 bg-black/30 flex items-center justify-center rounded-xl">
                                <span class="text-white text-sm font-medium">Cambiar imagen</span>
                            </div>
                        @else
                            <div class="flex flex-col items-center justify-center text-text/40">
                                <svg class="w-10 h-10 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span class="text-sm">Haz clic para subir imagen</span>
                            </div>
                        @endif
                        <input type="file" wire:model.live="imagen_contacto" class="hidden">
                    </label>
                    @error('imagen_contacto') <span class="text-accent text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Info contacto -->
                <div>
                    <label class="block mb-2 text-lg font-medium text-text">Motivos para llamar</label>
                    <textarea wire:model.blur.live='info_contacto' rows="3"
                        class="block w-full p-3 text-sm text-text bg-secondary/5 rounded-lg border border-text/20 focus:ring-primary focus:border-primary"
                        placeholder="Ej: presupuestos, urgencias, citas..."></textarea>
                    @error('info_contacto') <span class="text-accent text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Teléfono -->
                <div>
                    <label class="block mb-2 text-lg font-medium text-text">Teléfono</label>
                    <input type="text" wire:model.blur.live='telefono'
                        class="bg-secondary/5 border border-text/20 text-text text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-3"
                        placeholder="123456789">
                    @error('telefono') <span class="text-accent text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Email -->
                <div>
                    <label class="block mb-2 text-lg font-medium text-text">Email</label>
                    <input type="text" wire:model.blur.live='email'
                        class="bg-secondary/5 border border-text/20 text-text text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-3"
                        placeholder="ejemplo@correo.com">
                    @error('email') <span class="text-accent text-sm">{{ $message }}</span> @enderror
                </div>

            </div>
        </div>

        <!-- BOTÓN -->
        <div class="text-center pt-6">
            <button type="submit"
                class="text-white bg-primary hover:opacity-90 focus:ring-4 focus:ring-primary/30 font-medium rounded-lg text-lg px-8 py-3 focus:outline-none transition">
                Actualizar
            </button>
        </div>

    </form>
</div>
