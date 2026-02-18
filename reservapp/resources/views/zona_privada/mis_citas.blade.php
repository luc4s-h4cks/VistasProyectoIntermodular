<x-layouts::app :title="__('Mis Citas')">
    @include('partials.settings-heading')
    <flux:heading class="sr-only">{{ __('Profile Settings') }}</flux:heading>
    <x-settings.layout :heading="__('Mis Citas')" :subheading="__('Consulta informacion sobre tus citas.')">
        citas
    </x-settings.layout>

</x-layouts::app>
