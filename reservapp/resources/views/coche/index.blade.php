<x-layouts::app :title="__('Mis coches')">
    @include('partials.settings-heading')
    <flux:heading class="sr-only">{{ __('Profile Settings') }}</flux:heading>
    <x-settings.layout :heading="__('Mis Coches')" :subheading="__('Consulta informacion sobre tus vehiculos registrados.')">
        <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
            @foreach($miscoches as $coche)
            <div class="flex items-center gap-4 rounded-lg border p-4">
                <div>
                    <h3 class="text-lg font-medium text-gray-900">{{ $coche->marca }} {{ $coche->modelo }}</h3>
                    <p class="text-sm text-gray-500">{{ $coche->matricula }}</p>
                </div>
            </div>
            @endforeach

            {{-- Paginación --}}
            <div class="mt-6">
                {{ $miscoches->links() }}
            </div>

            {{-- Componente para crear nuevo coche --}}
            <div class="mt-6">
                <livewire:coche.create-coche />
            </div>
        </div>

    </x-settings.layout>

</x-layouts::app>
