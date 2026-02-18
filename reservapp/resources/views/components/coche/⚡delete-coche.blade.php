<?php

use Livewire\Component;

new class extends Component
{
    //
};
?>

<div>
    <form>
        @csrf
        @method('DELETE')
        <div class="p-6 space-y-6">
            <div class="flex items-center justify-between border-b pb-4">
                <flux:heading size="lg">{{ __('Eliminar coche') }}</flux:heading>
            </div>
            <p>{{ __('¿Estás seguro de que deseas eliminar este coche? Esta acción no se puede deshacer.') }}</p>
            <div class="border-t pt-4 space-y-3">
                <flux:button wire:click="$emit('closeModal')" variant="secondary">
                    {{ __('Cancelar') }}
                </flux:button>
                <flux:button wire:click="deleteCoche" variant="danger">
                    {{ __('Eliminar') }}
                </flux:button>
            </div>
        </div>
    </form>
</div>