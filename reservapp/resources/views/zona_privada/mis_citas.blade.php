<x-layouts::app :title="__('Mis Citas')">
    @include('partials.settings-heading')
    <flux:heading class="sr-only">{{ __('Profile Settings') }}</flux:heading>
    <x-settings.layout :heading="__('Mis Citas')" :subheading="__('Consulta información sobre tus citas.')">

        <div class="max-w-4xl mx-auto space-y-4">

            @forelse($miscitas as $cita)
                <livewire:cita.cita-card :cita="$cita" :key="$cita->id_cita" />
            @empty
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No tienes citas</h3>
                    <p class="mt-1 text-sm text-gray-500">Comienza solicitando una cita con tu taller de confianza.</p>
                </div>
            @endforelse

        </div>

    </x-settings.layout>
</x-layouts::app>
