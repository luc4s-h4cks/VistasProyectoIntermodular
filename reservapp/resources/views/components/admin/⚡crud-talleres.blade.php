<?php

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;
use App\Models\Taller;
use App\Models\Usuario;
use Livewire\Attributes\Validate;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

new class extends Component {
    use WithPagination, WithoutUrlPagination;

    public $buscar = '';
    public $campoBuscar = 'nombre';
    public $campoOrden = 'fecha_fin_suscripcion';
    public $orden = 'desc';
    public $tallerAEliminar = null;

    public $modalEliminar = false;
    public $modalAbierto = false;
    public $tallerSeleccionado = null;

    #[Validate('required|string|max:64')]
    public $nombre;

    #[Validate('nullable|string|max:255')]
    public $descripcion;

    #[Validate('nullable|string|max:64')]
    public $handle;

    #[Validate('nullable|string|max:255')]
    public $ubicacion;

    #[Validate('nullable|string|max:64')]
    public $email;

    #[Validate('nullable|string|max:12')]
    public $telefono;

    #[Validate('nullable|array')]
    public $tipo_vehiculo = [];

    #[Validate('nullable|array')]
    public $tipo_servicio = [];

    #[Validate('nullable|date')]
    public $fecha_fin_suscripcion;

    #[Validate('nullable|boolean')]
    public $suscripcion;

    #[Validate('required|exists:usuario,id_usuario')]
    public $id_usuario;

    protected function messages(): array
    {
        return [
            'nombre.required' => 'El nombre del taller es obligatorio.',
            'nombre.max' => 'El nombre no puede superar los 64 caracteres.',
            'descripcion.max' => 'La descripción no puede superar los 255 caracteres.',
            'handle.max' => 'El handle no puede superar los 64 caracteres.',
            'ubicacion.max' => 'La ubicación no puede superar los 255 caracteres.',
            'email.max' => 'El email no puede superar los 64 caracteres.',
            'telefono.max' => 'El teléfono no puede superar los 12 caracteres.',
            'id_usuario.required' => 'El usuario propietario es obligatorio.',
            'id_usuario.exists' => 'El usuario seleccionado no existe.',
        ];
    }

    public function updatingBuscar()
    {
        $this->resetPage();
    }

    public function ordenar($campo)
    {
        if ($this->campoOrden == $campo) {
            $this->orden = $this->orden == 'asc' ? 'desc' : 'asc';
        } else {
            $this->campoOrden = $campo;
            $this->orden = 'asc';
        }
    }

    public function abrirModal($id)
    {
        $taller = Taller::find($id);
        $this->tallerSeleccionado = $taller;

        $this->nombre = $taller->nombre;
        $this->descripcion = $taller->descripcion;
        $this->handle = $taller->handle;
        $this->ubicacion = $taller->ubicacion;
        $this->email = $taller->email;
        $this->telefono = $taller->telefono;
        $this->tipo_vehiculo = $taller->tipo_vehiculo ?? [];
        $this->tipo_servicio = $taller->tipo_servicio ?? [];
        $this->fecha_fin_suscripcion = $taller->fecha_fin_suscripcion;
        $this->suscripcion = $taller->suscripcion;
        $this->id_usuario = $taller->id_usuario;

        $this->modalAbierto = true;
        $this->resetValidation();
    }


    public function abrirModalEliminar($id)
    {
        $this->tallerAEliminar = Taller::with('citas')->find($id);
        $this->modalEliminar = true;
    }


    public function cerrarModal()
    {
        $this->modalAbierto = false;
        $this->tallerSeleccionado = null;
        $this->resetValidation();
    }

    public function cerrarModalEliminar()
    {
        $this->modalEliminar = false;
        $this->tallerAEliminar = null;
    }

    public function guardar()
    {
        $this->validate();

        Taller::find($this->tallerSeleccionado->id_taller)->update([
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'handle' => $this->handle,
            'ubicacion' => $this->ubicacion,
            'email' => $this->email,
            'telefono' => $this->telefono,
            'tipo_vehiculo' => $this->tipo_vehiculo,
            'tipo_servicio' => $this->tipo_servicio,
            'fecha_fin_suscripcion' => $this->fecha_fin_suscripcion,
            'suscripcion' => $this->suscripcion,
            'id_usuario' => $this->id_usuario,
        ]);

        $this->cerrarModal();
        session()->flash('message', 'Taller actualizado correctamente');
    }



    public function confirmarEliminar()
    {
        Taller::find($this->tallerAEliminar->id_taller)->delete();
        $this->cerrarModalEliminar();
        session()->flash('message', 'Taller eliminado correctamente');
    }

    public function render()
    {
        $talleres = Taller::with('usuario')
            ->where($this->campoBuscar, 'like', '%' . $this->buscar . '%')
            ->orderBy($this->campoOrden, $this->orden)
            ->paginate(10);

        $usuarios = Usuario::all(); // Para seleccionar propietario del taller

        return $this->view(['talleres' => $talleres, 'usuarios' => $usuarios]);
    }
};
?>

<div>

    @if (session()->has('message'))
        <div class="mb-4 bg-green-100 text-green-800 p-3 rounded-lg">{{ session('message') }}</div>
    @endif

    <!-- Buscador y botón crear -->
    <div class="mb-6 flex gap-3">
        <select wire:model.live="campoBuscar"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-3">
            <option value="nombre">Nombre</option>
            <option value="handle">Handle</option>
            <option value="ubicacion">Ubicación</option>
            <option value="email">Email</option>
            <option value="telefono">Teléfono</option>
        </select>
        <input type="text" wire:model.live="buscar" placeholder="Buscar..."
            class="flex-1 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-3">
    </div>

    <!-- Tabla -->
    <div class="overflow-x-auto rounded-xl shadow">
        <table class="w-full text-sm text-left text-gray-600">
            <thead class="bg-zinc-100 text-zinc-700 uppercase text-xs">
                <tr>
                    <th class="px-4 py-3 cursor-pointer" wire:click="ordenar('nombre')">
                        @if ($campoOrden == 'nombre')
                            <p class="text-blue-600">Nombre {!! $orden == 'asc' ? '↑' : '↓' !!}</p>
                        @else
                            <p>Nombre</p>
                        @endif
                    </th>
                    <th class="px-4 py-3 cursor-pointer" wire:click="ordenar('handle')">
                        @if ($campoOrden == 'handle')
                            <p class="text-blue-600">Handle {!! $orden == 'asc' ? '↑' : '↓' !!}</p>
                        @else
                            <p>Handle</p>
                        @endif
                    </th>
                    <th class="px-4 py-3 cursor-pointer" wire:click="ordenar('ubicacion')">
                        @if ($campoOrden == 'ubicacion')
                            <p class="text-blue-600">Ubicación {!! $orden == 'asc' ? '↑' : '↓' !!}</p>
                        @else
                            <p>Ubicación</p>
                        @endif
                    </th>
                    <th class="px-4 py-3 cursor-pointer" wire:click="ordenar('email')">
                        @if ($campoOrden == 'email')
                            <p class="text-blue-600">Email {!! $orden == 'asc' ? '↑' : '↓' !!}</p>
                        @else
                            <p>Email</p>
                        @endif
                    </th>
                    <th class="px-4 py-3 cursor-pointer" wire:click="ordenar('telefono')">
                        @if ($campoOrden == 'telefono')
                            <p class="text-blue-600">Teléfono {!! $orden == 'asc' ? '↑' : '↓' !!}</p>
                        @else
                            <p>Teléfono</p>
                        @endif
                    </th>
                    <th class="px-4 py-3 cursor-pointer" wire:click="ordenar('fecha_fin_suscripcion')">
                        @if ($campoOrden == 'fecha_fin_suscripcion')
                            <p class="text-blue-600">Suscripción {!! $orden == 'asc' ? '↑' : '↓' !!}</p>
                        @else
                            <p>Suscripción</p>
                        @endif
                    </th>
                    <th class="px-4 py-3 cursor-pointer">Propietario</th>
                    <th class="px-4 py-3 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($talleres as $taller)
                    <tr class="bg-white hover:bg-gray-50 transition">
                        <td class="px-4 py-3 font-medium text-gray-900">{{ $taller->nombre }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $taller->handle }}</td>
                        <td class="px-4 py-3">{{ $taller->ubicacion ?? '—' }}</td>
                        <td class="px-4 py-3">{{ $taller->email ?? '—' }}</td>
                        <td class="px-4 py-3">{{ $taller->telefono ?? '—' }}</td>
                        <td class="px-4 py-3">{{ $taller->fecha_fin_suscripcion?->format('d/m/Y') ?? '—' }}</td>
                        <td class="px-4 py-3">{{ $taller->usuario?->nombre_usuario ?? '—' }}</td>
                        <td class="px-4 py-3 text-center space-x-2">
                            <button wire:click="abrirModal('{{ $taller->id_taller }}')"
                                class="text-blue-600 hover:text-blue-800 text-xs font-medium">Editar</button>
                            <button wire:click="abrirModalEliminar('{{ $taller->id_taller }}')"
                                class="text-red-500 hover:text-red-700 text-xs font-medium">Eliminar</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-8 text-center text-gray-400">No se encontraron talleres</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="flex justify-center mt-4">
        {{ $talleres->links() }}
    </div>

    <!-- Modal Editar -->
    @if ($modalAbierto)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
            <div class="bg-white rounded-xl shadow-xl w-full max-w-lg p-6 space-y-4">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900">Editar taller</h3>
                    <button wire:click="cerrarModal" class="text-gray-400 hover:text-gray-600 text-xl">✕</button>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                        <input type="text" wire:model.blur="nombre"
                            class="w-full bg-gray-50 border border-gray-300 text-sm rounded-lg p-2.5">
                        @error('nombre')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Handle</label>
                        <input type="text" wire:model.blur="handle"
                            class="w-full bg-gray-50 border border-gray-300 text-sm rounded-lg p-2.5">
                        @error('handle')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                    <textarea wire:model.blur="descripcion" class="w-full bg-gray-50 border border-gray-300 text-sm rounded-lg p-2.5"></textarea>
                    @error('descripcion')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ubicación</label>
                    <input type="text" wire:model.blur="ubicacion"
                        class="w-full bg-gray-50 border border-gray-300 text-sm rounded-lg p-2.5">
                    @error('ubicacion')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" wire:model.blur="email"
                            class="w-full bg-gray-50 border border-gray-300 text-sm rounded-lg p-2.5">
                        @error('email')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                        <input type="text" wire:model.blur="telefono"
                            class="w-full bg-gray-50 border border-gray-300 text-sm rounded-lg p-2.5">
                        @error('telefono')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Propietario</label>
                        <select wire:model="id_usuario"
                            class="w-full bg-gray-50 border border-gray-300 text-sm rounded-lg p-2.5">
                            <option value="">-- Seleccionar usuario --</option>
                            @foreach ($usuarios as $usuario)
                                <option value="{{ $usuario->id_usuario }}">{{ $usuario->nombre_usuario }}</option>
                            @endforeach
                        </select>
                        @error('id_usuario')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Fecha fin suscripción</label>
                        <input type="date" wire:model.blur="fecha_fin_suscripcion"
                            class="w-full bg-gray-50 border border-gray-300 text-sm rounded-lg p-2.5">
                        @error('fecha_fin_suscripcion')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <button wire:click="cerrarModal"
                        class="px-4 py-2 text-sm text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200">Cancelar</button>
                    <button wire:click="guardar"
                        class="px-4 py-2 text-sm text-white bg-blue-600 rounded-lg hover:bg-blue-700">Guardar</button>
                </div>
            </div>
        </div>
    @endif


    <!-- Modal Eliminar -->
    @if ($modalEliminar)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
            <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6 space-y-4">

                <div class="flex items-center gap-3">
                    <div class="bg-red-100 p-2 rounded-full">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">Eliminar taller</h3>
                </div>

                <p class="text-gray-600 text-sm">
                    Vas a eliminar el taller <span class="font-semibold">{{ $tallerAEliminar->nombre }}</span>.
                </p>

                @if ($tallerAEliminar->citas->count() > 0)
                    <div class="bg-orange-50 border border-orange-200 rounded-lg p-3 text-sm text-orange-800">
                        <p class="font-semibold mb-1">⚠️ Este taller tiene citas asociadas</p>
                        <p>Al eliminarlo se eliminarán todas sus citas.</p>
                    </div>
                @else
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-3 text-sm text-gray-600">
                        <p>Este taller no tiene citas asociadas. Se eliminarán sus datos.</p>
                    </div>
                @endif

                <div class="flex justify-end gap-3 pt-2">
                    <button wire:click="cerrarModalEliminar"
                        class="px-4 py-2 text-sm text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200">
                        Cancelar
                    </button>
                    <button wire:click="confirmarEliminar"
                        class="px-4 py-2 text-sm text-white bg-red-600 rounded-lg hover:bg-red-700">
                        Sí, eliminar
                    </button>
                </div>

            </div>
        </div>
    @endif

</div>
