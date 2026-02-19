<?php

use Livewire\Component;
use App\Models\Coche;

new class extends Component
{
    public bool $mostrar = false;
    public ?int $carId = null;

    public function mount(Coche $coche): void
    {
        $this->carId = $coche->id_coche; // ✅ usa el nombre correcto de tu PK
    }

    public function abrirModal(): void
    {
        $this->mostrar = true;
    }

    public function cerrarModal(): void
    {
        $this->mostrar = false;
    }

    public function deleteCoche(): void
    {
        Coche::findOrFail($this->carId)->delete();
        $this->cerrarModal();
        $this->dispatch('cocheEliminado');
    }
};
?>

<div>
    <flux:button wire:click="abrirModal">
        Eliminar Coche
    </flux:button>

    @if($mostrar)
    {{-- Overlay --}}
    <div
        class="fixed inset-0 z-40 bg-black/50 backdrop-blur-sm dark:bg-black/70"
        wire:click="cerrarModal"
    ></div>

    {{-- Modal --}}
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="w-full max-w-lg rounded-xl shadow-2xl
                    bg-white text-gray-900
                    dark:bg-gray-900 dark:text-gray-100">
            <form>
                <div class="p-6 space-y-6">

                    {{-- Header --}}
                    <div class="flex items-center justify-between border-b pb-4
                                border-gray-200 dark:border-gray-700">
                        <flux:heading size="lg">
                            {{ __('Eliminar coche') }}
                        </flux:heading>
                        <button
                            type="button"
                            wire:click="cerrarModal"
                            class="rounded-lg p-1.5 transition
                                   text-gray-400 hover:bg-gray-100 hover:text-gray-600
                                   dark:text-gray-500 dark:hover:bg-gray-800 dark:hover:text-gray-300"
                        >
                            <flux:icon.x-mark class="size-5" />
                        </button>
                    </div>

                    {{-- Body --}}
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        {{ __('¿Estás seguro de que deseas eliminar este coche? Esta acción no se puede deshacer.') }}
                    </p>

                    {{-- Footer --}}
                    <div class="border-t pt-4 flex justify-end gap-3
                                border-gray-200 dark:border-gray-700">
                        <flux:button type="button" wire:click="cerrarModal">
                            {{ __('Cancelar') }}
                        </flux:button>
                        <flux:button
                            type="button"
                            wire:click="deleteCoche"
                            class="!bg-red-600 hover:!bg-red-700 !text-white !border-red-600"
                        >
                            <span wire:loading.remove wire:target="deleteCoche">{{ __('Eliminar') }}</span>
                            <span wire:loading wire:target="deleteCoche">{{ __('Eliminando…') }}</span>
                        </flux:button>
                    </div>

                </div>
            </form>
        </div>
    </div>
    @endif
</div>