<?php

use Livewire\Component;
use App\Models\Coche;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;

new class extends Component
{
    public $miscoches = [];

    public function mount(): void
    {
        $this->cargarCoches();
    }

    #[On('cocheCreado')]
    public function cargarCoches(): void
    {
        $this->miscoches = Coche::where('id_usuario', Auth::id())->get();
    }

    #[On('cocheEliminado')]
    public function refrescarCoches(): void
    {
        $this->miscoches = Coche::where('id_usuario', auth()->id())->get(); // ✅ miscoches, no coches
    }
};
?>

<div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">

    {{-- Botón para añadir coche --}}
    <div>
        <livewire:coche.create-coche />
    </div>

    {{-- Lista de coches --}}
    @forelse($miscoches as $coche)
        <div class="flex items-center gap-4 rounded-lg border border-secondary/20 p-4">
            <div>
                <h3 class="text-lg font-medium text-text">
                    {{ $coche->marca }} {{ $coche->modelo }}
                </h3>
                <p class="text-sm text-text/60">
                    {{ $coche->matricula }}
                </p>
            </div>
            <div class="ml-auto">
                <livewire:coche.edit-coche :coche="$coche" />
            </div>
            <div>
                <livewire:coche.delete-coche :coche="$coche" />
            </div>
        </div>
    @empty
        <div class="rounded-lg border border-dashed border-secondary/30 p-8 text-center text-sm text-text/40">
            No tienes vehículos registrados aún.
        </div>
    @endforelse

</div>
