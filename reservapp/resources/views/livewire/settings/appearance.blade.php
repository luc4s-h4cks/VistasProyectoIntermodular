{{--
    ESTA VISTA YA NO SE USA
    
    El selector de apariencia (tema claro/oscuro/sistema) ahora está integrado en:
    - Vista: resources/views/livewire/settings/profile.blade.php (sección Apariencia)
    - No requiere lógica backend, Flux UI lo maneja con Alpine.js y localStorage
    
    El componente Appearance.php está vacío.
--}}
<section class="w-full">
    @include('partials.settings-heading')

    <flux:heading class="sr-only">{{ __('Appearance Settings') }}</flux:heading>

    <x-settings.layout :heading="__('Appearance')" :subheading=" __('Update the appearance settings for your account')">
        <flux:radio.group x-data variant="segmented" x-model="$flux.appearance">
            <flux:radio value="light" icon="sun">{{ __('Light') }}</flux:radio>
            <flux:radio value="dark" icon="moon">{{ __('Dark') }}</flux:radio>
            <flux:radio value="system" icon="computer-desktop">{{ __('System') }}</flux:radio>
        </flux:radio.group>
    </x-settings.layout>
</section>
