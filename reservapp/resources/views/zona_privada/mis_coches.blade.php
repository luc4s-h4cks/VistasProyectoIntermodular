<x-layouts::app :title="__('Mis coches')">
    @include('partials.settings-heading')
    <flux:heading class="sr-only">{{ __('Profile Settings') }}</flux:heading>
    <x-settings.layout :heading="__('Mis Coches')" :subheading="__('Consulta informacion sobre tus vehiculos registrados.')">

        <livewire:coche.mostrar-coches />

    </x-settings.layout>
</x-layouts::app>
