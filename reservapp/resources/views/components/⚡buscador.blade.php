<?php

use Livewire\Component;

new class extends Component
{
    public $talleres = [];

    public function mount($talleres)
    {
        $this->talleres = $talleres;
    }
};
?>

<div class="w-3/5 mx-auto">
    <h1> Buscador </h1>
    <br/>
    <flux:input icon="magnifying-glass" placeholder="Buscar talleres..." clearable />

    <div class="mt-6 flex flex-col gap-4">
        @foreach ($talleres as $taller)
            <div class="w-full flex gap-4 bg-background border-l-4 border-primary rounded-xl shadow-sm p-4">

                {{-- Imagen --}}
                <div class="flex-shrink-0">
                    @if ($taller->img_perfil)
                        <img src="{{ asset('storage/imgTalleres/' . $taller->img_perfil) }}"
                             alt="{{ $taller->nombre }}"
                             class="w-24 h-24 object-cover rounded-lg border-2 border-secondary">
                    @else
                        <div class="w-24 h-24 bg-zinc-200 rounded-lg flex items-center justify-center text-zinc-400 text-xs text-center">
                            Sin imagen
                        </div>
                    @endif
                </div>

                {{-- Info --}}
                <div class="flex flex-col justify-center gap-1">
                    <h2 class="text-lg font-bold text-primary">{{ $taller->nombre }}</h2>
                    <p class="text-sm text-text">{{ $taller->descripcion }}</p>
                    <p class="text-sm text-zinc-500">
                        <span class="font-semibold text-accent">Ubicación:</span> {{ $taller->info_contacto }}
                    </p>
                </div>

            </div>
        @endforeach
    </div>
</div>
