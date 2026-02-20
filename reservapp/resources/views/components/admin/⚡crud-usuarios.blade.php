<?php

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;
use App\Models\Usuario;
use Livewire\Attributes\Validate;
use Illuminate\Validation\Rule;

new class extends Component {
    use WithPagination, WithoutUrlPagination;

    public $buscar = '';
    public $campoBuscar = 'nombre';
    public $campoOrden = 'fecha_creacion_cuenta';
    public $orden = 'desc';
    public $usuarioAEliminar = null;

    public $modalCrear = false;
    public $modalEliminar = false;
    public $modalAbierto = false;
    public $usuarioSeleccionado = null;

    #[Validate('required|string|max:255')]
    public $nombre;

    #[Validate('nullable|string|max:255')]
    public $apellidos;

    #[Validate('required|string|max:255')]
    public $nombre_usuario;

    #[Validate('required|email|max:255')]
    public $email;

    #[Validate('nullable|string|max:20')]
    public $telefono;

    #[Validate('required|date|before:today')]
    public $fecha_nacimiento;

    #[Validate('nullable|integer|in:0,1,2')]
    public $tipo;

    #[Validate('nullable|string|min:8')]
    public $pass;

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
        $usuario = Usuario::find($id);
        $this->usuarioSeleccionado = $usuario;
        $this->nombre = $usuario->nombre;
        $this->apellidos = $usuario->apellidos;
        $this->nombre_usuario = $usuario->nombre_usuario;
        $this->email = $usuario->email;
        $this->telefono = $usuario->telefono;
        $this->tipo = $usuario->tipo;
        $this->resetValidation();
        $this->modalAbierto = true;
    }

    public function abrirModalCrear()
    {
        $this->reset('nombre', 'apellidos', 'nombre_usuario', 'email', 'telefono', 'tipo', 'pass', 'fecha_nacimiento');
        $this->resetValidation();
        $this->modalCrear = true;
    }

    public function abrirModalEliminar($id)
    {
        $this->usuarioAEliminar = Usuario::with('taller')->find($id);
        $this->modalEliminar = true;
    }

    public function cerrarModalCrear()
    {
        $this->modalCrear = false;
        $this->resetValidation();
    }

    public function cerrarModal()
    {
        $this->modalAbierto = false;
        $this->usuarioSeleccionado = null;
        $this->resetValidation();
    }

    public function cerrarModalEliminar()
    {
        $this->modalEliminar = false;
        $this->usuarioAEliminar = null;
    }

    // handle único ignorando el propio usuario al editar
    public function updatedNombreUsuario()
    {
        $this->validateOnly('nombre_usuario', [
            'nombre_usuario' => ['required', 'string', 'max:255', $this->modalAbierto ? Rule::unique('usuario', 'nombre_usuario')->ignore($this->usuarioSeleccionado?->id_usuario, 'id_usuario') : 'unique:usuario,nombre_usuario'],
        ]);
    }

    public function updatedEmail()
    {
        $this->validateOnly('email', [
            'email' => ['required', 'email', 'max:255', $this->modalAbierto ? Rule::unique('usuario', 'email')->ignore($this->usuarioSeleccionado?->id_usuario, 'id_usuario') : 'unique:usuario,email'],
        ]);
    }

    public function guardar()
    {
        $this->validate();

        Usuario::find($this->usuarioSeleccionado->id_usuario)->update([
            'nombre' => $this->nombre,
            'apellidos' => $this->apellidos,
            'nombre_usuario' => $this->nombre_usuario,
            'email' => $this->email,
            'telefono' => $this->telefono,
            'tipo' => $this->tipo,
        ]);

        $this->cerrarModal();
        session()->flash('message', 'Usuario actualizado correctamente');
    }

    public function crear()
    {
        $this->validate();

        Usuario::create([
            'nombre' => $this->nombre,
            'apellidos' => $this->apellidos,
            'nombre_usuario' => $this->nombre_usuario,
            'email' => $this->email,
            'telefono' => $this->telefono,
            'tipo' => $this->tipo,
            'pass' => bcrypt($this->pass),
            'fecha_nacimiento' => $this->fecha_nacimiento,
            'fecha_creacion_cuenta' => now(),
        ]);

        $this->cerrarModalCrear();
        session()->flash('message', 'Usuario creado correctamente');
    }

    public function confirmarEliminar()
    {
        Usuario::find($this->usuarioAEliminar->id_usuario)->delete();
        $this->cerrarModalEliminar();
        session()->flash('message', 'Usuario eliminado correctamente');
    }

    public function render()
    {
        $usuarios = Usuario::where($this->campoBuscar, 'like', '%' . $this->buscar . '%')
            ->orderBy($this->campoOrden, $this->orden)
            ->paginate(10);

        return $this->view(['usuarios' => $usuarios]);
    }
};
?>

<div>

    @if (session()->has('message'))
        <div class="mb-4 bg-green-100 text-green-800 p-3 rounded-lg">{{ session('message') }}</div>
    @endif

    <div class="mb-6 flex gap-3">
        <select wire:model.live="campoBuscar"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-3">
            <option value="nombre">Nombre</option>
            <option value="apellidos">Apellidos</option>
            <option value="nombre_usuario">Usuario</option>
            <option value="email">Email</option>
            <option value="telefono">Teléfono</option>
        </select>
        <input type="text" wire:model.live="buscar" placeholder="Buscar..."
            class="flex-1 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-3">
        <button wire:click="abrirModalCrear"
            class="px-4 py-2 text-sm text-white bg-green-600 rounded-lg hover:bg-green-700">
            + Nuevo usuario
        </button>
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
                    <th class="px-4 py-3 cursor-pointer" wire:click="ordenar('nombre_usuario')">
                        @if ($campoOrden == 'nombre_usuario')
                            <p class="text-blue-600">Usuario {!! $orden == 'asc' ? '↑' : '↓' !!}</p>
                        @else
                            <p>Usuario</p>
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
                    <th class="px-4 py-3 cursor-pointer" wire:click="ordenar('tipo')">
                        @if ($campoOrden == 'tipo')
                            <p class="text-blue-600">Tipo {!! $orden == 'asc' ? '↑' : '↓' !!}</p>
                        @else
                            <p>Tipo</p>
                        @endif
                    </th>
                    <th class="px-4 py-3 cursor-pointer" wire:click="ordenar('fecha_creacion_cuenta')">
                        @if ($campoOrden == 'fecha_creacion_cuenta')
                            <p class="text-blue-600">Registro {!! $orden == 'asc' ? '↑' : '↓' !!}</p>
                        @else
                            <p>Registro</p>
                        @endif
                    </th>
                    <th class="px-4 py-3 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($usuarios as $usuario)
                    <tr class="bg-white hover:bg-gray-50 transition">
                        <td class="px-4 py-3 font-medium text-gray-900">{{ $usuario->nombre }} {{ $usuario->apellidos }}
                        </td>
                        <td class="px-4 py-3 text-gray-500">{{ $usuario->nombre_usuario }}</td>
                        <td class="px-4 py-3">{{ $usuario->email }}</td>
                        <td class="px-4 py-3">{{ $usuario->telefono ?? '—' }}</td>
                        <td class="px-4 py-3">
                            @if ($usuario->tipo == Usuario::ADMIN)
                                <span
                                    class="bg-red-100 text-red-700 text-xs font-medium px-2 py-1 rounded-full">Admin</span>
                            @elseif ($usuario->tipo == Usuario::MECANICO)
                                <span
                                    class="bg-blue-100 text-blue-700 text-xs font-medium px-2 py-1 rounded-full">Taller</span>
                            @else
                                <span
                                    class="bg-gray-100 text-gray-700 text-xs font-medium px-2 py-1 rounded-full">Usuario</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">{{ $usuario->fecha_creacion_cuenta?->format('d/m/Y') ?? '—' }}</td>
                        <td class="px-4 py-3 text-center space-x-2">
                            <button wire:click="abrirModal({{ $usuario->id_usuario }})"
                                class="text-blue-600 hover:text-blue-800 text-xs font-medium">Editar</button>
                            <button wire:click="abrirModalEliminar({{ $usuario->id_usuario }})"
                                class="text-red-500 hover:text-red-700 text-xs font-medium">Eliminar</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-gray-400">No se encontraron usuarios</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="flex justify-center mt-4">
        {{ $usuarios->links() }}
    </div>

    <!-- Modal Editar -->
    @if ($modalAbierto)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
            <div class="bg-white rounded-xl shadow-xl w-full max-w-lg p-6 space-y-4">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900">Editar usuario</h3>
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
                        <label class="block text-sm font-medium text-gray-700 mb-1">Apellidos</label>
                        <input type="text" wire:model.blur="apellidos"
                            class="w-full bg-gray-50 border border-gray-300 text-sm rounded-lg p-2.5">
                        @error('apellidos')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nombre de usuario</label>
                    <input type="text" wire:model.blur="nombre_usuario"
                        class="w-full bg-gray-50 border border-gray-300 text-sm rounded-lg p-2.5">
                    @error('nombre_usuario')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>
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
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipo</label>
                    <select wire:model="tipo" class="w-full bg-gray-50 border border-gray-300 text-sm rounded-lg p-2.5">
                        <option value="0">Usuario</option>
                        <option value="1">Taller</option>
                        <option value="2">Admin</option>
                    </select>
                    @error('tipo')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
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

    <!-- Modal Crear -->
    @if ($modalCrear)
        <form action="">
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
                <div class="bg-white rounded-xl shadow-xl w-full max-w-lg p-6 space-y-4">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-900">Nuevo usuario</h3>
                        <button wire:click="cerrarModalCrear"
                            class="text-gray-400 hover:text-gray-600 text-xl">✕</button>
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
                            <label class="block text-sm font-medium text-gray-700 mb-1">Apellidos</label>
                            <input type="text" wire:model.blur="apellidos"
                                class="w-full bg-gray-50 border border-gray-300 text-sm rounded-lg p-2.5">
                            @error('apellidos')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nombre de usuario</label>
                        <input type="text" wire:model.blur="nombre_usuario"
                            class="w-full bg-gray-50 border border-gray-300 text-sm rounded-lg p-2.5">
                        @error('nombre_usuario')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" wire:model.blur.live="email"
                            class="w-full bg-gray-50 border border-gray-300 text-sm rounded-lg p-2.5">
                        @error('email')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                            <input type="text" wire:model.blur="telefono"
                                class="w-full bg-gray-50 border border-gray-300 text-sm rounded-lg p-2.5">
                            @error('telefono')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tipo</label>
                            <select wire:model.number="tipo"
                                class="w-full bg-gray-50 border border-gray-300 text-sm rounded-lg p-2.5">

                                <option value="0">Usuario</option>
                                <option value="1">Taller</option>
                                <option value="2">Admin</option>
                            </select>
                            @error('tipo')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Contraseña</label>
                        <input type="password" wire:model.blur="pass"
                            class="w-full bg-gray-50 border border-gray-300 text-sm rounded-lg p-2.5">
                        @error('pass')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de nacimiento</label>
                        <input type="date" wire:model.blur="fecha_nacimiento"
                            class="w-full bg-gray-50 border border-gray-300 text-sm rounded-lg p-2.5">
                        @error('fecha_nacimiento')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="flex justify-end gap-3 pt-2">
                        <button wire:click="cerrarModalCrear"
                            class="px-4 py-2 text-sm text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200">Cancelar</button>
                        <button wire:click="crear"
                            class="px-4 py-2 text-sm text-white bg-green-600 rounded-lg hover:bg-green-700">Crear</button>
                    </div>
                </div>
            </div>
        </form>
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
                    <h3 class="text-lg font-semibold text-gray-900">Eliminar usuario</h3>
                </div>

                <p class="text-gray-600 text-sm">
                    Vas a eliminar a <span class="font-semibold">{{ $usuarioAEliminar->nombre }}
                        {{ $usuarioAEliminar->apellidos }}</span>
                    (<span class="text-gray-500">{{ $usuarioAEliminar->nombre_usuario }}</span>).
                </p>

                @if ($usuarioAEliminar->taller)
                    <div class="bg-orange-50 border border-orange-200 rounded-lg p-3 text-sm text-orange-800">
                        <p class="font-semibold mb-1">⚠️ Este usuario tiene un taller asociado</p>
                        <p>Taller: <span class="font-medium">{{ $usuarioAEliminar->taller->nombre }}</span></p>
                        <p class="mt-1">Al eliminar el usuario se eliminarán también el taller y todas sus citas.</p>
                    </div>
                @else
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-3 text-sm text-gray-600">
                        <p>Este usuario no tiene taller asociado. Se eliminarán sus datos y citas.</p>
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
