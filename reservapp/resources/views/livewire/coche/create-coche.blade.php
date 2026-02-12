<div>
    {{-- Botón para abrir modal --}}
    <flux:button wire:click="openModal" variant="primary">
        {{ __('Nuevo coche') }}
    </flux:button>

    {{-- Modal --}}
    <flux:modal wire:model="showModal" name="create-coche" focusable class="max-w-2xl">
        <div class="p-6 space-y-6">
            <div class="flex items-center justify-between border-b pb-4">
                <flux:heading size="lg">{{ __('Nuevo coche') }}</flux:heading>
                <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <form action="{{ route('coche.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <flux:input type="text" name="matricula" :label="__('Matrícula')" required />
                    <flux:input type="text" name="marca" :label="__('Marca')" required />
                    <flux:input type="text" name="modelo" :label="__('Modelo')" required />
                    <flux:input type="text" name="n_bastidor" :label="__('Número de bastidor')" required />
                </div>
                <div class="border-t pt-4 space-y-3">
                    <label class="text-red-600 font-medium text-sm">{{ __('Cambiar foto Coche') }}</label>
                    
                    <div class="bg-gray-400 rounded-lg h-48 flex items-center justify-center text-white">
                        <span class="text-lg">{{ __('Foto vehículo') }}</span>
                    </div>
                    
                    <flux:input
                        type="file"
                        name="img_vehiculo"
                        accept="image/*"
                        :label="__('Seleccionar imagen')"
                    />

                    <flux:input type="text" name="tipo_conbustible" :label="__('Tipo de combustible')" required />
                </div>
                <div class="flex justify-end border-t pt-4">
                    <flux:button type="submit" variant="primary">{{ __('Añadir') }}</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>
</div>
