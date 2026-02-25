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

    #[Validate('required|string|max:32')]
    public $nombre;

    #[Validate('nullable|string|max:32')]
    public $apellidos;

    #[Validate('required|string|max:32')]
    public $nombre_usuario;

    #[Validate('required|email|max:64')]
    public $email;

    #[Validate('nullable|string|max:12')]
    public $telefono;

    #[Validate('required|date|before:today')]
    public $fecha_nacimiento;

    #[Validate('nullable|integer|in:0,1,2')]
    public $tipo;

    #[Validate('nullable|string|min:8')]
    public $pass;

    protected function messages(): array {
        return [
            'nombre.required'           => 'El nombre es obligatorio.',
            'nombre.max'                => 'El nombre no puede superar los 32 caracteres.',
            'apellidos.max'             => 'Los apellidos no pueden superar los 32 caracteres.',
            'nombre_usuario.required'   => 'El nombre de usuario es obligatorio.',
            'nombre_usuario.max'        => 'El nombre de usuario no puede superar los 32 caracteres.',
            'email.required'            => 'El correo electrónico es obligatorio.',
            'email.email'               => 'El formato del correo no es válido.',
            'email.max'                 => 'El correo no puede superar los 64 caracteres.',
            'telefono.max'              => 'El teléfono no puede superar los 12 caracteres.',
            'fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria.',
            'fecha_nacimiento.date'     => 'La fecha de nacimiento no es válida.',
            'fecha_nacimiento.before'   => 'La fecha de nacimiento debe ser anterior a hoy.',
            'tipo.in'                   => 'El tipo de usuario no es válido.',
            'pass.min'                  => 'La contraseña debe tener al menos 8 caracteres.',
            'pass.required'             => 'La contraseña es obligatoria.',
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
            class="bg-background border border-text/20 text-text text-sm rounded-lg focus:ring-primary focus:border-primary p-3
                   dark:text-[#f5f5f5] dark:border-[#f5f5f5]/20">
            <option value="nombre">Nombre</option>
            <option value="apellidos">Apellidos</option>
            <option value="nombre_usuario">Usuario</option>
            <option value="email">Email</option>
            <option value="telefono">Teléfono</option>
        </select>
        <input type="text" wire:model.live="buscar" placeholder="Buscar..."
            class="flex-1 bg-background border border-text/20 text-text text-sm rounded-lg focus:ring-primary focus:border-primary p-3
                   dark:text-[#f5f5f5] dark:border-[#f5f5f5]/20">
        <button wire:click="abrirModalCrear"
            class="px-4 py-2 text-sm text-white bg-primary rounded-lg hover:bg-primary/80">
            + Nuevo usuario
        </button>
    </div>
    <!-- Tabla -->
    <div class="overflow-x-auto rounded-xl shadow">
        <table class="w-full text-sm text-left text-text dark:text-[#f5f5f5]">
            <thead class="bg-primary/10 text-primary uppercase text-xs dark:bg-primary/20">
                <tr>
                    <th class="px-4 py-3 cursor-pointer" wire:click="ordenar('nombre')">
                        @if ($campoOrden == 'nombre')
                            <span class="text-secondary">Nombre {!! $orden == 'asc' ? '↑' : '↓' !!}</span>
                        @else
                            Nombre
                        @endif
                    </th>
                    <th class="px-4 py-3 cursor-pointer" wire:click="ordenar('nombre_usuario')">
                        @if ($campoOrden == 'nombre_usuario')
                            <span class="text-secondary">Usuario {!! $orden == 'asc' ? '↑' : '↓' !!}</span>
                        @else
                            Usuario
                        @endif
                    </th>
                    <th class="px-4 py-3 cursor-pointer" wire:click="ordenar('email')">
                        @if ($campoOrden == 'email')
                            <span class="text-secondary">Email {!! $orden == 'asc' ? '↑' : '↓' !!}</span>
                        @else
                            Email
                        @endif
                    </th>
                    <th class="px-4 py-3 cursor-pointer" wire:click="ordenar('telefono')">
                        @if ($campoOrden == 'telefono')
                            <span class="text-secondary">Teléfono {!! $orden == 'asc' ? '↑' : '↓' !!}</span>
                        @else
                            Teléfono
                        @endif
                    </th>
                    <th class="px-4 py-3 cursor-pointer" wire:click="ordenar('tipo')">
                        @if ($campoOrden == 'tipo')
                            <span class="text-secondary">Tipo {!! $orden == 'asc' ? '↑' : '↓' !!}</span>
                        @else
                            Tipo
                        @endif
                    </th>
                    <th class="px-4 py-3 cursor-pointer" wire:click="ordenar('fecha_creacion_cuenta')">
                        @if ($campoOrden == 'fecha_creacion_cuenta')
                            <span class="text-secondary">Registro {!! $orden == 'asc' ? '↑' : '↓' !!}</span>
                        @else
                            Registro
                        @endif
                    </th>
                    <th class="px-4 py-3 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-text/10 dark:divide-[#f5f5f5]/10">
                @forelse ($usuarios as $usuario)
                    <tr class="bg-background hover:bg-primary/5 transition dark:hover:bg-primary/10">
                        <td class="px-4 py-3 font-medium">{{ $usuario->nombre }} {{ $usuario->apellidos }}</td>
                        <td class="px-4 py-3 text-text/60 dark:text-[#f5f5f5]/60">{{ $usuario->nombre_usuario }}</td>
                        <td class="px-4 py-3">{{ $usuario->email }}</td>
                        <td class="px-4 py-3">{{ $usuario->telefono ?? '—' }}</td>
                        <td class="px-4 py-3">
                            @if ($usuario->tipo == Usuario::ADMIN)
                                <span class="bg-accent/10 text-accent text-xs font-medium px-2 py-1 rounded-full">Admin</span>
                            @elseif ($usuario->tipo == Usuario::MECANICO)
                                <span class="bg-primary/10 text-primary text-xs font-medium px-2 py-1 rounded-full">Taller</span>
                            @else
                                <span class="bg-secondary/10 text-secondary text-xs font-medium px-2 py-1 rounded-full">Usuario</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-text/60 dark:text-[#f5f5f5]/60">{{ $usuario->fecha_creacion_cuenta?->format('d/m/Y') ?? '—' }}</td>
                        <td class="px-4 py-3 text-center space-x-2">
                            <button wire:click="abrirModal({{ $usuario->id_usuario }})"
                                class="text-primary hover:text-primary/70 text-xs font-medium">Editar</button>
                            <button wire:click="abrirModalEliminar({{ $usuario->id_usuario }})"
                                class="text-accent hover:text-accent/70 text-xs font-medium">Eliminar</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-text/40 dark:text-[#f5f5f5]/40">No se encontraron usuarios</td>
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
        <div class="fixed inset-0 z-50 flex items-center justify-center">
            <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" wire:click="cerrarModal"></div>
            <div class="relative z-10 w-full max-w-lg rounded-xl p-6 shadow-2xl bg-background text-text dark:text-[#f5f5f5]">
                <div class="mb-6 flex items-start justify-between border-b pb-4 border-secondary/30 dark:border-secondary/20">
                    <div>
                        <h3 class="text-lg font-semibold text-primary">Editar usuario</h3>
                        <p class="text-sm text-text/60 dark:text-[#f5f5f5]/60">Modifica los datos del usuario.</p>
                    </div>
                    <button wire:click="cerrarModal"
                        class="rounded-lg p-1.5 transition text-text/40 hover:bg-secondary/10 hover:text-accent dark:text-[#f5f5f5]/40">✕</button>
                </div>
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm text-text/50 dark:text-[#f5f5f5]/50 mb-1">Nombre</label>
                            <input type="text" wire:model.blur="nombre"
                                class="w-full bg-transparent border-0 border-b-2 text-sm text-text dark:text-[#f5f5f5] border-text/20 dark:border-[#f5f5f5]/20 focus:border-primary focus:outline-none focus:ring-0 py-2.5">
                            @error('nombre') <span class="text-xs text-accent">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm text-text/50 dark:text-[#f5f5f5]/50 mb-1">Apellidos</label>
                            <input type="text" wire:model.blur="apellidos"
                                class="w-full bg-transparent border-0 border-b-2 text-sm text-text dark:text-[#f5f5f5] border-text/20 dark:border-[#f5f5f5]/20 focus:border-primary focus:outline-none focus:ring-0 py-2.5">
                            @error('apellidos') <span class="text-xs text-accent">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm text-text/50 dark:text-[#f5f5f5]/50 mb-1">Nombre de usuario</label>
                        <input type="text" wire:model.blur="nombre_usuario"
                            class="w-full bg-transparent border-0 border-b-2 text-sm text-text dark:text-[#f5f5f5] border-text/20 dark:border-[#f5f5f5]/20 focus:border-primary focus:outline-none focus:ring-0 py-2.5">
                        @error('nombre_usuario') <span class="text-xs text-accent">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm text-text/50 dark:text-[#f5f5f5]/50 mb-1">Email</label>
                        <input type="email" wire:model.blur="email"
                            class="w-full bg-transparent border-0 border-b-2 text-sm text-text dark:text-[#f5f5f5] border-text/20 dark:border-[#f5f5f5]/20 focus:border-primary focus:outline-none focus:ring-0 py-2.5">
                        @error('email') <span class="text-xs text-accent">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm text-text/50 dark:text-[#f5f5f5]/50 mb-1">Teléfono</label>
                        <input type="text" wire:model.blur="telefono"
                            class="w-full bg-transparent border-0 border-b-2 text-sm text-text dark:text-[#f5f5f5] border-text/20 dark:border-[#f5f5f5]/20 focus:border-primary focus:outline-none focus:ring-0 py-2.5">
                        @error('telefono') <span class="text-xs text-accent">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm text-text/50 dark:text-[#f5f5f5]/50 mb-1">Tipo</label>
                        <select wire:model="tipo"
                            class="w-full bg-transparent border-0 border-b-2 text-sm text-text dark:text-[#f5f5f5] border-text/20 dark:border-[#f5f5f5]/20 focus:border-primary focus:outline-none focus:ring-0 py-2.5">
                            <option value="0">Usuario</option>
                            <option value="1">Taller</option>
                            <option value="2">Admin</option>
                        </select>
                        @error('tipo') <span class="text-xs text-accent">{{ $message }}</span> @enderror
                    </div>
                    <div class="flex justify-end gap-3 pt-4 border-t border-secondary/20">
                        <flux:button type="button" wire:click="cerrarModal" variant="ghost">Cancelar</flux:button>
                        <flux:button type="button" wire:click="guardar" variant="primary">Guardar</flux:button>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <!-- Modal Crear -->
    @if ($modalCrear)
        <form action="">
            <div class="fixed inset-0 z-50 flex items-center justify-center">
                <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" wire:click="cerrarModalCrear"></div>
                <div class="relative z-10 w-full max-w-lg rounded-xl p-6 shadow-2xl bg-background text-text dark:text-[#f5f5f5]">
                    <div class="mb-6 flex items-start justify-between border-b pb-4 border-secondary/30 dark:border-secondary/20">
                        <div>
                            <h3 class="text-lg font-semibold text-primary">Nuevo usuario</h3>
                            <p class="text-sm text-text/60 dark:text-[#f5f5f5]/60">Rellena los datos del nuevo usuario.</p>
                        </div>
                        <button wire:click="cerrarModalCrear"
                            class="rounded-lg p-1.5 transition text-text/40 hover:bg-secondary/10 hover:text-accent dark:text-[#f5f5f5]/40">✕</button>
                    </div>
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm text-text/50 dark:text-[#f5f5f5]/50 mb-1">Nombre</label>
                                <input type="text" wire:model.blur="nombre"
                                    class="w-full bg-transparent border-0 border-b-2 text-sm text-text dark:text-[#f5f5f5] border-text/20 dark:border-[#f5f5f5]/20 focus:border-primary focus:outline-none focus:ring-0 py-2.5">
                                @error('nombre') <span class="text-xs text-accent">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm text-text/50 dark:text-[#f5f5f5]/50 mb-1">Apellidos</label>
                                <input type="text" wire:model.blur="apellidos"
                                    class="w-full bg-transparent border-0 border-b-2 text-sm text-text dark:text-[#f5f5f5] border-text/20 dark:border-[#f5f5f5]/20 focus:border-primary focus:outline-none focus:ring-0 py-2.5">
                                @error('apellidos') <span class="text-xs text-accent">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm text-text/50 dark:text-[#f5f5f5]/50 mb-1">Nombre de usuario</label>
                            <input type="text" wire:model.blur="nombre_usuario"
                                class="w-full bg-transparent border-0 border-b-2 text-sm text-text dark:text-[#f5f5f5] border-text/20 dark:border-[#f5f5f5]/20 focus:border-primary focus:outline-none focus:ring-0 py-2.5">
                            @error('nombre_usuario') <span class="text-xs text-accent">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm text-text/50 dark:text-[#f5f5f5]/50 mb-1">Email</label>
                            <input type="email" wire:model.blur.live="email"
                                class="w-full bg-transparent border-0 border-b-2 text-sm text-text dark:text-[#f5f5f5] border-text/20 dark:border-[#f5f5f5]/20 focus:border-primary focus:outline-none focus:ring-0 py-2.5">
                            @error('email') <span class="text-xs text-accent">{{ $message }}</span> @enderror
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm text-text/50 dark:text-[#f5f5f5]/50 mb-1">Teléfono</label>
                                <input type="text" wire:model.blur="telefono"
                                    class="w-full bg-transparent border-0 border-b-2 text-sm text-text dark:text-[#f5f5f5] border-text/20 dark:border-[#f5f5f5]/20 focus:border-primary focus:outline-none focus:ring-0 py-2.5">
                                @error('telefono') <span class="text-xs text-accent">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm text-text/50 dark:text-[#f5f5f5]/50 mb-1">Tipo</label>
                                <select wire:model.number="tipo"
                                    class="w-full bg-transparent border-0 border-b-2 text-sm text-text dark:text-[#f5f5f5] border-text/20 dark:border-[#f5f5f5]/20 focus:border-primary focus:outline-none focus:ring-0 py-2.5">
                                    <option value="-1" disabled>Seleccione el tipo de usuario</option>
                                    <option value="0">Usuario</option>
                                    <option value="1">Taller</option>
                                    <option value="2">Admin</option>
                                </select>
                                @error('tipo') <span class="text-xs text-accent">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm text-text/50 dark:text-[#f5f5f5]/50 mb-1">Contraseña</label>
                            <input type="password" wire:model.blur="pass"
                                class="w-full bg-transparent border-0 border-b-2 text-sm text-text dark:text-[#f5f5f5] border-text/20 dark:border-[#f5f5f5]/20 focus:border-primary focus:outline-none focus:ring-0 py-2.5">
                            @error('pass') <span class="text-xs text-accent">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm text-text/50 dark:text-[#f5f5f5]/50 mb-1">Fecha de nacimiento</label>
                            <input type="date" wire:model.blur="fecha_nacimiento"
                                class="w-full bg-transparent border-0 border-b-2 text-sm text-text dark:text-[#f5f5f5] border-text/20 dark:border-[#f5f5f5]/20 focus:border-primary focus:outline-none focus:ring-0 py-2.5">
                            @error('fecha_nacimiento') <span class="text-xs text-accent">{{ $message }}</span> @enderror
                        </div>
                        <div class="flex justify-end gap-3 pt-4 border-t border-secondary/20">
                            <flux:button type="button" wire:click="cerrarModalCrear" variant="ghost">Cancelar</flux:button>
                            <flux:button type="button" wire:click="crear" variant="primary">Crear</flux:button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    @endif
    <!-- Modal Eliminar -->
    @if ($modalEliminar)
        <div class="fixed inset-0 z-50 flex items-center justify-center">
            <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" wire:click="cerrarModalEliminar"></div>
            <div class="relative z-10 w-full max-w-md rounded-xl p-6 shadow-2xl bg-background text-text dark:text-[#f5f5f5] space-y-4">
                <div class="flex items-center gap-3">
                    <div class="bg-accent/10 p-2 rounded-full">
                        <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-primary">Eliminar usuario</h3>
                </div>
                <p class="text-sm text-text/70 dark:text-[#f5f5f5]/70">
                    Vas a eliminar a <span class="font-semibold">{{ $usuarioAEliminar->nombre }} {{ $usuarioAEliminar->apellidos }}</span>
                    (<span class="text-text/50 dark:text-[#f5f5f5]/50">{{ $usuarioAEliminar->nombre_usuario }}</span>).
                </p>
                @if ($usuarioAEliminar->taller)
                    <div class="bg-secondary/10 border border-secondary/30 rounded-lg p-3 text-sm text-text/70 dark:text-[#f5f5f5]/70">
                        <p class="font-semibold mb-1">⚠️ Este usuario tiene un taller asociado</p>
                        <p>Taller: <span class="font-medium">{{ $usuarioAEliminar->taller->nombre }}</span></p>
                        <p class="mt-1">Al eliminar el usuario se eliminarán también el taller y todas sus citas.</p>
                    </div>
                @else
                    <div class="bg-secondary/10 border border-secondary/30 rounded-lg p-3 text-sm text-text/70 dark:text-[#f5f5f5]/70">
                        <p>Este usuario no tiene taller asociado. Se eliminarán sus datos y citas.</p>
                    </div>
                @endif
                <div class="flex justify-end gap-3 pt-2 border-t border-secondary/20">
                    <flux:button type="button" wire:click="cerrarModalEliminar" variant="ghost">Cancelar</flux:button>
                    <flux:button type="button" wire:click="confirmarEliminar" variant="danger">
                        <span wire:loading.remove wire:target="confirmarEliminar">Sí, eliminar</span>
                        <span wire:loading wire:target="confirmarEliminar">Eliminando…</span>
                    </flux:button>
                </div>
            </div>
        </div>
    @endif
</div>
