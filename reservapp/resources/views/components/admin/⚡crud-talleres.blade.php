<?php

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;
use App\Models\Taller;
use App\Models\Usuario;
use App\Models\TipoSuscripcion;
use Livewire\Attributes\Validate;

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
    public $tallerSeleccionadoId = null;

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

    #[Validate('nullable|date')]
    public $fecha_fin_suscripcion;

    #[Validate('nullable|exists:tipo_suscripcion,id_estado')]
    public $id_suscripcion = null;

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
        $this->tallerSeleccionadoId = (string) $taller->id_taller;

        $this->nombre = $taller->nombre;
        $this->descripcion = $taller->descripcion;
        $this->handle = $taller->handle;
        $this->ubicacion = $taller->ubicacion;
        $this->email = $taller->email;
        $this->telefono = $taller->telefono;
        $this->fecha_fin_suscripcion = $taller->fecha_fin_suscripcion ? \Carbon\Carbon::parse($taller->fecha_fin_suscripcion)->format('Y-m-d') : null;
        $this->id_suscripcion = $taller->id_suscripcion;
        $this->id_usuario = (string) $taller->id_usuario;

        $this->resetValidation();
        $this->modalAbierto = true;
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
        $this->tallerSeleccionadoId = null;
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

        Taller::find($this->tallerSeleccionadoId)->update([
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'handle' => $this->handle,
            'ubicacion' => $this->ubicacion,
            'email' => $this->email,
            'telefono' => $this->telefono,
            'fecha_fin_suscripcion' => $this->fecha_fin_suscripcion ?: null,
            'id_suscripcion' => $this->id_suscripcion ?: null,
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

        $usuarios = Usuario::all();
        $tiposSuscripcion = TipoSuscripcion::all();

        return $this->view(['talleres' => $talleres, 'usuarios' => $usuarios, 'tiposSuscripcion' => $tiposSuscripcion]);
    }
};
?>

<div>
    @if (session()->has('message'))
        <div class="mb-4 bg-green-100 text-green-800 p-3 rounded-lg">{{ session('message') }}</div>
    @endif

    <!-- Buscador -->
    <div class="mb-6 flex gap-3">
        <select wire:model.live="campoBuscar"
            class="bg-background border border-text/20 text-text text-sm rounded-lg focus:ring-primary focus:border-primary p-3
                   dark:text-[#f5f5f5] dark:border-[#f5f5f5]/20">
            <option value="nombre">Nombre</option>
            <option value="handle">Handle</option>
            <option value="ubicacion">Ubicación</option>
            <option value="email">Email</option>
            <option value="telefono">Teléfono</option>
        </select>
        <input type="text" wire:model.live="buscar" placeholder="Buscar..."
            class="flex-1 bg-background border border-text/20 text-text text-sm rounded-lg focus:ring-primary focus:border-primary p-3
                   dark:text-[#f5f5f5] dark:border-[#f5f5f5]/20">
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
                    <th class="px-4 py-3 cursor-pointer" wire:click="ordenar('handle')">
                        @if ($campoOrden == 'handle')
                            <span class="text-secondary">Handle {!! $orden == 'asc' ? '↑' : '↓' !!}</span>
                        @else
                            Handle
                        @endif
                    </th>
                    <th class="px-4 py-3 cursor-pointer" wire:click="ordenar('ubicacion')">
                        @if ($campoOrden == 'ubicacion')
                            <span class="text-secondary">Ubicación {!! $orden == 'asc' ? '↑' : '↓' !!}</span>
                        @else
                            Ubicación
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
                    <th class="px-4 py-3 cursor-pointer" wire:click="ordenar('fecha_fin_suscripcion')">
                        @if ($campoOrden == 'fecha_fin_suscripcion')
                            <span class="text-secondary">Suscripción {!! $orden == 'asc' ? '↑' : '↓' !!}</span>
                        @else
                            Suscripción
                        @endif
                    </th>
                    <th class="px-4 py-3">Propietario</th>
                    <th class="px-4 py-3 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-text/10 dark:divide-[#f5f5f5]/10">
                @forelse ($talleres as $taller)
                    <tr class="bg-background hover:bg-primary/5 transition dark:hover:bg-primary/10">
                        <td class="px-4 py-3 font-medium">{{ $taller->nombre }}</td>
                        <td class="px-4 py-3 text-text/60 dark:text-[#f5f5f5]/60">{{ $taller->handle ?? '—' }}</td>
                        <td class="px-4 py-3">{{ $taller->ubicacion ?? '—' }}</td>
                        <td class="px-4 py-3">{{ $taller->email ?? '—' }}</td>
                        <td class="px-4 py-3">{{ $taller->telefono ?? '—' }}</td>
                        <td class="px-4 py-3">
                            {{ $taller->fecha_fin_suscripcion ? \Carbon\Carbon::parse($taller->fecha_fin_suscripcion)->format('d/m/Y') : '—' }}
                        </td>
                        <td class="px-4 py-3">{{ $taller->usuario?->nombre_usuario ?? '—' }}</td>
                        <td class="px-4 py-3 text-center space-x-2">
                            <button wire:click="abrirModal('{{ $taller->id_taller }}')"
                                class="text-primary hover:text-primary/70 text-xs font-medium">Editar</button>
                            <button wire:click="abrirModalEliminar('{{ $taller->id_taller }}')"
                                class="text-accent hover:text-accent/70 text-xs font-medium">Eliminar</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-8 text-center text-text/40 dark:text-[#f5f5f5]/40">No se
                            encontraron talleres</td>
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
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
            <div class="w-full max-w-lg rounded-xl p-6 shadow-2xl bg-background text-text dark:text-[#f5f5f5]">
                <div
                    class="mb-6 flex items-start justify-between border-b pb-4 border-secondary/30 dark:border-secondary/20">
                    <div>
                        <h3 class="text-lg font-semibold text-primary">Editar taller</h3>
                        <p class="text-sm text-text/60 dark:text-[#f5f5f5]/60">Modifica los datos del taller.</p>
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
                            @error('nombre')
                                <span class="text-xs text-accent">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm text-text/50 dark:text-[#f5f5f5]/50 mb-1">Handle</label>
                            <input type="text" wire:model.blur="handle"
                                class="w-full bg-transparent border-0 border-b-2 text-sm text-text dark:text-[#f5f5f5] border-text/20 dark:border-[#f5f5f5]/20 focus:border-primary focus:outline-none focus:ring-0 py-2.5">
                            @error('handle')
                                <span class="text-xs text-accent">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm text-text/50 dark:text-[#f5f5f5]/50 mb-1">Descripción</label>
                        <textarea wire:model.blur="descripcion" rows="2"
                            class="w-full bg-transparent border-0 border-b-2 text-sm text-text dark:text-[#f5f5f5] border-text/20 dark:border-[#f5f5f5]/20 focus:border-primary focus:outline-none focus:ring-0 py-2.5 resize-none"></textarea>
                        @error('descripcion')
                            <span class="text-xs text-accent">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm text-text/50 dark:text-[#f5f5f5]/50 mb-1">Ubicación</label>
                        <input type="text" wire:model.blur="ubicacion"
                            class="w-full bg-transparent border-0 border-b-2 text-sm text-text dark:text-[#f5f5f5] border-text/20 dark:border-[#f5f5f5]/20 focus:border-primary focus:outline-none focus:ring-0 py-2.5">
                        @error('ubicacion')
                            <span class="text-xs text-accent">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm text-text/50 dark:text-[#f5f5f5]/50 mb-1">Email</label>
                            <input type="email" wire:model.blur="email"
                                class="w-full bg-transparent border-0 border-b-2 text-sm text-text dark:text-[#f5f5f5] border-text/20 dark:border-[#f5f5f5]/20 focus:border-primary focus:outline-none focus:ring-0 py-2.5">
                            @error('email')
                                <span class="text-xs text-accent">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm text-text/50 dark:text-[#f5f5f5]/50 mb-1">Teléfono</label>
                            <input type="text" wire:model.blur="telefono"
                                class="w-full bg-transparent border-0 border-b-2 text-sm text-text dark:text-[#f5f5f5] border-text/20 dark:border-[#f5f5f5]/20 focus:border-primary focus:outline-none focus:ring-0 py-2.5">
                            @error('telefono')
                                <span class="text-xs text-accent">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm text-text/50 dark:text-[#f5f5f5]/50 mb-1">Propietario</label>
                            <select wire:model="id_usuario"
                                class="w-full bg-transparent border-0 border-b-2 text-sm text-text dark:text-[#f5f5f5] border-text/20 dark:border-[#f5f5f5]/20 focus:border-primary focus:outline-none focus:ring-0 py-2.5">
                                <option value="">-- Seleccionar --</option>
                                @foreach ($usuarios as $usuario)
                                    <option value="{{ $usuario->id_usuario }}">{{ $usuario->nombre_usuario }}</option>
                                @endforeach
                            </select>
                            @error('id_usuario')
                                <span class="text-xs text-accent">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm text-text/50 dark:text-[#f5f5f5]/50 mb-1">Fin
                                suscripción</label>
                            <input type="date" wire:model.blur="fecha_fin_suscripcion"
                                class="w-full bg-transparent border-0 border-b-2 text-sm text-text dark:text-[#f5f5f5] border-text/20 dark:border-[#f5f5f5]/20 focus:border-primary focus:outline-none focus:ring-0 py-2.5">
                            @error('fecha_fin_suscripcion')
                                <span class="text-xs text-accent">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm text-text/50 dark:text-[#f5f5f5]/50 mb-1">Tipo de
                            suscripción</label>
                        <select wire:model="id_suscripcion"
                            class="w-full bg-transparent border-0 border-b-2 text-sm text-text dark:text-[#f5f5f5] border-text/20 dark:border-[#f5f5f5]/20 focus:border-primary focus:outline-none focus:ring-0 py-2.5">
                            <option value="">— Sin suscripción —</option>
                            @foreach ($tiposSuscripcion as $tipo)
                                @if ($tipo->id_estado == $taller->id_suscripcion)
                                    <option value="{{ $tipo->id_estado }}" selected>{{ $tipo->nombre }}
                                        ({{ number_format($tipo->precio, 2) }}€)
                                    </option>
                                @else
                                    <option value="{{ $tipo->id_estado }}">{{ $tipo->nombre }}
                                        ({{ number_format($tipo->precio, 2) }}€)</option>
                                @endif
                            @endforeach
                        </select>
                        @error('id_suscripcion')
                            <span class="text-xs text-accent">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t border-secondary/20">
                        <button type="button" wire:click="cerrarModal"
                            class="px-4 py-2 text-sm font-medium text-text/70 hover:text-text transition">Cancelar</button>
                        <button type="button" wire:click="guardar"
                            class="px-5 py-2 text-sm font-semibold text-white bg-primary rounded-lg hover:bg-primary/80 transition">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal Eliminar -->
    @if ($modalEliminar)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
            <div
                class="w-full max-w-md rounded-xl p-6 shadow-2xl bg-background text-text dark:text-[#f5f5f5] space-y-4">
                <div class="flex items-center gap-3">
                    <div class="bg-accent/10 p-2 rounded-full">
                        <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-primary">Eliminar taller</h3>
                </div>

                <p class="text-sm text-text/70 dark:text-[#f5f5f5]/70">
                    Vas a eliminar el taller <span class="font-semibold">{{ $tallerAEliminar->nombre }}</span>.
                </p>

                @if ($tallerAEliminar->citas->count() > 0)
                    <div
                        class="bg-secondary/10 border border-secondary/30 rounded-lg p-3 text-sm text-text/70 dark:text-[#f5f5f5]/70">
                        <p class="font-semibold mb-1">⚠️ Este taller tiene citas asociadas</p>
                        <p>Al eliminarlo se eliminarán también todas sus citas.</p>
                    </div>
                @else
                    <div
                        class="bg-secondary/10 border border-secondary/30 rounded-lg p-3 text-sm text-text/70 dark:text-[#f5f5f5]/70">
                        <p>Este taller no tiene citas asociadas. Se eliminarán sus datos.</p>
                    </div>
                @endif

                <div class="flex justify-end gap-3 pt-2 border-t border-secondary/20">
                    <button type="button" wire:click="cerrarModalEliminar"
                        class="px-4 py-2 text-sm font-medium text-text/70 hover:text-text transition">Cancelar</button>
                    <button type="button" wire:click="confirmarEliminar"
                        class="px-5 py-2 text-sm font-semibold text-white bg-accent rounded-lg hover:bg-accent/80 transition">
                        <span wire:loading.remove wire:target="confirmarEliminar">Sí, eliminar</span>
                        <span wire:loading wire:target="confirmarEliminar">Eliminando…</span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
