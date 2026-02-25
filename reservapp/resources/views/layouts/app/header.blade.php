<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-background text-text">

    {{-- HEADER PRINCIPAL (desktop + móvil) --}}
    <flux:header container class="header">
        {{-- sin sidebar toggle. Siempre tenemos header --}}

        <x-app-logo href="{{ route('home') }}" wire:navigate />

        <flux:navbar class="-mb-px max-md:hidden">
            <flux:navbar.item icon="magnifying-glass" :href="route('buscador')"
                :current="request()->routeIs('buscador')" wire:navigate>
                {{ __('Buscador') }}
            </flux:navbar.item>
        </flux:navbar>


        {{-- Solo en movil --}}
        <flux:navbar class="md:hidden me-1.5 space-x-0.5 rtl:space-x-reverse py-0!">
            <flux:navbar.item icon="magnifying-glass" :href="route('buscador')"
                :current="request()->routeIs('buscador')" wire:navigate />
        </flux:navbar>

        <flux:spacer />

        <flux:navbar class="me-1.5 space-x-0.5 rtl:space-x-reverse py-0!">

            @if (auth()->user() && auth()->user()->tipo == 1)
                <flux:navbar.item icon="wrench" :href="route('gestion-citas')"
                    :current="request()->routeIs('gestion-citas')" wire:navigate>
                    <span class="max-md:hidden">{{ __('Taller') }}</span>
                </flux:navbar.item>
            @endif

            @if (auth()->user() && auth()->user()->tipo == 2)
                <flux:navbar.item icon="wrench" :href="route('admin.usuarios')"
                    :current="request()->routeIs('admin.usuarios')" wire:navigate>
                    <span class="max-md:hidden">{{ __('Administracion') }}</span>
                </flux:navbar.item>
            @endif

        </flux:navbar>



        @if (auth()->user())
            <x-desktop-user-menu />
        @else
            <flux:navbar class="me-1.5 space-x-0.5 rtl:space-x-reverse py-0!">
                <flux:navbar.item icon="user-circle" :href="route('login')" wire:navigate>
                    <span class="max-md:hidden">{{ __('Login') }}</span>
                </flux:navbar.item>
                <flux:navbar.item icon="inbox-arrow-down" :href="route('register')" wire:navigate>
                    <span class="max-md:hidden">{{ __('Register') }}</span>
                </flux:navbar.item>
            </flux:navbar>
        @endif

    </flux:header>

    {{ $slot }}

    @fluxScripts
</body>

</html>