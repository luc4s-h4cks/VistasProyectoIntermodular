<?php

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;
use App\Models\Coche;
use App\Models\TipoCombustible;
use Illuminate\Validation\Rule;

new class extends Component {
    use WithPagination, WithoutUrlPagination;

    public $buscar = '';
    public $campoBuscar = 'matricula';
    public $campoOrden = 'matricula';
    public $orden = 'asc';

    public $modalEditar = false;
    public $modalEliminar = false;
    public $cocheSeleccionado = null;
    public $cocheAEliminar = null;
    public $tiposPropulsion = [];

    public string $matricula        = '';
    public string $marca            = '';
    public string $modelo           = '';
    public string $n_bastidor       = '';
    public string $tipo_combustible = '';

    public function mount(): void
    {
        $this->tiposPropulsion = TipoCombustible::all();
    }

    public function updatingBuscar(): void
    {
        $this->resetPage();
    }

    public function ordenar($campo): void
    {
        if ($this->campoOrden == $campo) {
            $this->orden = $this->orden == 'asc' ? 'desc' : 'asc';
        } else {
            $this->campoOrden = $campo;
            $this->orden = 'asc';
        }
    }

    public function abrirModalEditar($id): void
    {
        $coche = Coche::find($id);
        $this->cocheSeleccionado  = $coche;
        $this->matricula          = $coche->matricula;
        $this->marca              = $coche->marca;
        $this->modelo             = $coche->modelo;
        $this->n_bastidor         = $coche->n_bastidor;
        $this->tipo_combustible   = $coche->tipo_combustible;
        $this->resetValidation();
        $this->modalEditar = true;
    }

    public function cerrarModalEditar(): void
    {
        $this->modalEditar = false;
        $this->cocheSeleccionado = null;
        $this->resetValidation();
    }

    public function abrirModalEliminar($id): void
    {
        $this->cocheAEliminar = Coche::find($id);
        $this->modalEliminar = true;
    }

    public function cerrarModalEliminar(): void
    {
        $this->modalEliminar = false;
        $this->cocheAEliminar = null;
    }

    public function guardar(): void
    {
        $this->validate([
            'matricula'        => ['required', 'string', 'max:10', Rule::unique('coche', 'matricula')->ignore($this->cocheSeleccionado->id_coche, 'id_coche')],
            'marca'            => 'required|string|max:100',
            'modelo'           => 'required|string|max:100',
            'n_bastidor'       => ['required', 'string', 'max:17', Rule::unique('coche', 'n_bastidor')->ignore($this->cocheSeleccionado->id_coche, 'id_coche')],
            'tipo_combustible' => 'required|integer|exists:tipo_propulsion,tipo_combustible',
        ]);

        Coche::find($this->cocheSeleccionado->id_coche)->update([
            'matricula'        => strtoupper($this->matricula),
            'marca'            => $this->marca,
            'modelo'           => $this->modelo,
            'n_bastidor'       => strtoupper($this->n_bastidor),
            'tipo_combustible' => $this->tipo_combustible,
        ]);

        $this->cerrarModalEditar();
        session()->flash('message', 'Coche actualizado correctamente.');
    }

    public function confirmarEliminar(): void
    {
        Coche::find($this->cocheAEliminar->id_coche)->delete();
        $this->cerrarModalEliminar();
        session()->flash('message', 'Coche eliminado correctamente.');
    }

    public function render()
    {
        $coches = Coche::with('usuario')
            ->when($this->campoBuscar === 'usuario', function ($q) {
                $q->whereHas('usuario', fn($u) => $u->where('nombre', 'like', '%' . $this->buscar . '%'));
            }, function ($q) {
                $q->where($this->campoBuscar, 'like', '%' . $this->buscar . '%');
            })
            ->orderBy($this->campoOrden, $this->orden)
            ->paginate(10);

        return $this->view(['coches' => $coches]);
    }
?>

<div>

    @if (session()->has('message'))
        <div class="mb-4 bg-green-100 text-green-800 p-3 rounded-lg">{{ session('message') }}</div>
    @endif

    {{-- Buscador --}}
    <div class="mb-6 flex gap-3">
        <select wire:model.live="campoBuscar"
            class="bg-background border border-text/20 text-text text-sm rounded-lg focus:ring-primary focus:border-primary p-3
                   dark:text-[#f5f5f5] dark:border-[#f5f5f5]/20">
            <option value="matricula">Matrícula</option>
            <option value="marca">Marca</option>
            <option value="modelo">Modelo</option>
            <option value="n_bastidor">Bastidor</option>
            <option value="id_usuario">Propietario</option>
        </select>
        <input type="text" wire:model.live="buscar" placeholder="Buscar..."
            class="flex-1 bg-background border border-text/20 text-text text-sm rounded-lg focus:ring-primary focus:border-primary p-3
                   dark:text-[#f5f5f5] dark:border-[#f5f5f5]/20">
    </div>

    {{-- Tabla --}}
    <div class="overflow-x-auto rounded-xl shadow">
        <table class="w-full text-sm text-left text-text dark:text-[#f5f5f5]">
            <thead class="bg-primary/10 text-primary uppercase text-xs dark:bg-primary/20">
                <tr>
                    @foreach ([
                        'matricula' => 'Matrícula',
                        'marca' => 'Marca',
                        'modelo' => 'Modelo',
                        'n_bastidor' => 'Bastidor',
                        'tipo_combustible'=> 'Propulsión',
                        'id_usuario' => 'Propietario',
                    ] as $campo => $label)
                        <th class="px-4 py-3 cursor-pointer" wire:click="ordenar('{{ $campo }}')">
                            @if ($campoOrden == $campo)
                                <span class="text-secondary">{{ $label }} {!! $orden == 'asc' ? '↑' : '↓' !!}</span>
                            @else
                                {{ $label }}
                            @endif
                        </th>
                    @endforeach
                    <th class="px-4 py-3 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-text/10 dark:divide-[#f5f5f5]/10">
                @forelse ($coches as $coche)
                    <tr class="bg-background hover:bg-primary/5 transition dark:hover:bg-primary/10">
                        <td class="px-4 py-3 font-medium uppercase">{{ $coche->matricula }}</td>
                        <td class="px-4 py-3">{{ $coche->marca }}</td>
                        <td class="px-4 py-3">{{ $coche->modelo }}</td>
                        <td class="px-4 py-3 uppercase text-text/60 dark:text-[#f5f5f5]/60">{{ $coche->n_bastidor }}</td>
                        <td class="px-4 py-3">
                            <span class="bg-primary/10 text-primary text-xs font-medium px-2 py-1 rounded-full">
                                {{ $coche->tipoCombustible->nombre ?? '—' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center space-x-2">
                            <button wire:click="abrirModalEditar({{ $coche->id_coche }})"
                                class="text-primary hover:text-primary/70 text-xs font-medium">Editar</button>
                            <button wire:click="abrirModalEliminar({{ $coche->id_coche }})"
                                class="text-accent hover:text-accent/70 text-xs font-medium">Eliminar</button>
                        </td>
                        <td class="px-4 py-3 text-text/60 dark:text-[#f5f5f5]/60">
                            {{ $coche->usuario->nombre ?? '—' }}
                            <span class="text-xs text-text/40 dark:text-[#f5f5f5]/40 block">
                                {{ $coche->usuario->nombre_usuario ?? '' }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-text/40 dark:text-[#f5f5f5]/40">
                            No se encontraron coches
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="flex justify-center mt-4">
        {{ $coches->links() }}
    </div>

    {{-- Modal Editar --}}
    @if ($modalEditar)
        <div class="fixed inset-0 z-50 flex items-center justify-center">
            <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" wire:click="cerrarModalEditar"></div>

            <div class="relative z-10 w-full max-w-lg rounded-xl p-6 shadow-2xl bg-background text-text dark:text-[#f5f5f5]">

                {{-- Header --}}
                <div class="mb-6 flex items-start justify-between border-b pb-4 border-secondary/30 dark:border-secondary/20">
                    <div>
                        <h3 class="text-lg font-semibold text-primary">Editar Coche</h3>
                        <p class="text-sm text-text/60 dark:text-[#f5f5f5]/60">Modifica los datos del vehículo.</p>
                    </div>
                    <button wire:click="cerrarModalEditar"
                        class="rounded-lg p-1.5 transition text-text/40 hover:bg-secondary/10 hover:text-accent dark:text-[#f5f5f5]/40">
                        <flux:icon.x-mark class="size-5" />
                    </button>
                </div>

                <form wire:submit="guardar" class="space-y-4">

                    {{-- Matrícula --}}
                    <div class="relative z-0 w-full group">
                        <input wire:model="matricula" type="text" id="edit_matricula" placeholder=" "
                            class="block py-2.5 px-0 w-full text-sm bg-transparent border-0 border-b-2 appearance-none focus:outline-none focus:ring-0 peer uppercase
                                   text-text border-text/20 focus:border-primary
                                   dark:text-[#f5f5f5] dark:border-[#f5f5f5]/20 dark:focus:border-primary" />
                        <label for="edit_matricula"
                            class="peer-focus:font-medium absolute text-sm text-text/50 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0]
                                   peer-focus:text-primary peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0
                                   peer-focus:scale-75 peer-focus:-translate-y-6 dark:text-[#f5f5f5]/50">
                            Matrícula
                        </label>
                        @error('matricula') <p class="mt-1 text-xs text-accent">{{ $message }}</p> @enderror
                    </div>

                    {{-- Marca y Modelo --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div class="relative z-0 w-full group">
                            <input wire:model="marca" type="text" id="edit_marca" placeholder=" "
                                class="block py-2.5 px-0 w-full text-sm bg-transparent border-0 border-b-2 appearance-none focus:outline-none focus:ring-0 peer
                                       text-text border-text/20 focus:border-primary
                                       dark:text-[#f5f5f5] dark:border-[#f5f5f5]/20 dark:focus:border-primary" />
                            <label for="edit_marca"
                                class="peer-focus:font-medium absolute text-sm text-text/50 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0]
                                       peer-focus:text-primary peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0
                                       peer-focus:scale-75 peer-focus:-translate-y-6 dark:text-[#f5f5f5]/50">
                                Marca
                            </label>
                            @error('marca') <p class="mt-1 text-xs text-accent">{{ $message }}</p> @enderror
                        </div>
                        <div class="relative z-0 w-full group">
                            <input wire:model="modelo" type="text" id="edit_modelo" placeholder=" "
                                class="block py-2.5 px-0 w-full text-sm bg-transparent border-0 border-b-2 appearance-none focus:outline-none focus:ring-0 peer
                                       text-text border-text/20 focus:border-primary
                                       dark:text-[#f5f5f5] dark:border-[#f5f5f5]/20 dark:focus:border-primary" />
                            <label for="edit_modelo"
                                class="peer-focus:font-medium absolute text-sm text-text/50 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0]
                                       peer-focus:text-primary peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0
                                       peer-focus:scale-75 peer-focus:-translate-y-6 dark:text-[#f5f5f5]/50">
                                Modelo
                            </label>
                            @error('modelo') <p class="mt-1 text-xs text-accent">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Bastidor --}}
                    <div class="relative z-0 w-full group">
                        <input wire:model="n_bastidor" type="text" id="edit_bastidor" placeholder=" "
                            class="block py-2.5 px-0 w-full text-sm bg-transparent border-0 border-b-2 appearance-none focus:outline-none focus:ring-0 peer uppercase
                                   text-text border-text/20 focus:border-primary
                                   dark:text-[#f5f5f5] dark:border-[#f5f5f5]/20 dark:focus:border-primary" />
                        <label for="edit_bastidor"
                            class="peer-focus:font-medium absolute text-sm text-text/50 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0]
                                   peer-focus:text-primary peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0
                                   peer-focus:scale-75 peer-focus:-translate-y-6 dark:text-[#f5f5f5]/50">
                            Número de bastidor
                        </label>
                        @error('n_bastidor') <p class="mt-1 text-xs text-accent">{{ $message }}</p> @enderror
                    </div>

                    {{-- Tipo de propulsión --}}
                    <div class="relative z-0 w-full group">
                        <label for="edit_tipo" class="block text-sm text-text/50 mb-1 dark:text-[#f5f5f5]/50">
                            Tipo de propulsión
                        </label>
                        <select wire:model="tipo_combustible" id="edit_tipo"
                            class="block py-2.5 px-0 w-full text-sm bg-transparent border-0 border-b-2 appearance-none focus:outline-none focus:ring-0
                                   text-text border-text/20 focus:border-primary
                                   dark:text-[#f5f5f5] dark:border-[#f5f5f5]/20 dark:focus:border-primary">
                            <option value="" disabled>Selecciona el tipo de propulsión</option>
                            @foreach ($tiposPropulsion as $tipo)
                                <option value="{{ $tipo->tipo_combustible }}">{{ $tipo->nombre }}</option>
                            @endforeach
                        </select>
                        @error('tipo_combustible') <p class="mt-1 text-xs text-accent">{{ $message }}</p> @enderror
                    </div>

                    {{-- Botones --}}
                    <div class="flex justify-end gap-3 pt-4 border-t border-secondary/20">
                        <flux:button type="button" wire:click="cerrarModalEditar" variant="ghost">Cancelar</flux:button>
                        <flux:button type="submit" variant="primary">
                            <span wire:loading.remove wire:target="guardar">Guardar cambios</span>
                            <span wire:loading wire:target="guardar">Guardando…</span>
                        </flux:button>
                    </div>

                </form>
            </div>
        </div>
    @endif

    {{-- Modal Eliminar --}}
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
                    <h3 class="text-lg font-semibold text-primary">Eliminar coche</h3>
                </div>

                <p class="text-sm text-text/70 dark:text-[#f5f5f5]/70">
                    Vas a eliminar el coche
                    <span class="font-semibold uppercase">{{ $cocheAEliminar->matricula }}</span>
                    — <span class="text-text/50 dark:text-[#f5f5f5]/50">{{ $cocheAEliminar->marca }} {{ $cocheAEliminar->modelo }}</span>.
                </p>

                <div class="bg-secondary/10 border border-secondary/30 rounded-lg p-3 text-sm text-text/70 dark:text-[#f5f5f5]/70">
                    <p>Esta acción eliminará el coche y todos sus registros asociados. Esta operación no se puede deshacer.</p>
                </div>

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
