<?php

use Livewire\Component;

new class extends Component
{
    //
};
?>

<div>
    <flux:modal wire:model="showModal" name="modal-cita" focusable class="max-w-2xl">
        <div class="p-6 space-y-6">
            <div class="flex items-center justify-between border-b pb-4">
                <div>Nombre del Taller: {{ $nombreTaller }}</div>
                <div>Fecha de entrega: {{ $fechaEntrega }}</div>
            </div>
            <div class="flex items-center justify-between border-b pb-4">
                <flux:button variant="{{ $btnVariant }}">{{ $btn }}</flux:button>
                <div>EStado: {{ $estado }}</div>
            </div>
        </div>
    </flux:modal>
</div>