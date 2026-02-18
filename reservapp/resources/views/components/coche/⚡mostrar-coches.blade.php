<?php

use Livewire\Component;
use App\Models\Coche;
use Livewire\Attributes\On;

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
        //$this->miscoches = Coche::where('user_id', auth()->id())->latest()->get();
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
        <div class="flex items-center gap-4 rounded-lg border p-4">
            <div>
                <h3 class="text-lg font-medium text-gray-900">
                    {{ $coche->marca }} {{ $coche->modelo }}
                </h3>
                <p class="text-sm text-gray-500">{{ $coche->matricula }}</p>
            </div>
        </div>
    @empty
        <div class="rounded-lg border border-dashed p-8 text-center text-sm text-gray-400">
            No tienes vehículos registrados aún.
        </div>
    @endforelse

    {{-- Paginación (activar cuando uses paginate()) --}}
    {{-- <div class="mt-6">{{ $miscoches->links() }}</div> --}}

</div>
