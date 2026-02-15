<x-layouts::auth>
    <div class="flex flex-col gap-6">
        <x-auth-header :title="__('Create an account')" :description="__('Enter your details below to create your account')" />

        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('register.store') }}" class="flex flex-col gap-6">
            @csrf

            <!-- Nombre real -->
            <flux:input name="nombre" label="Nombre" value="{{ old('nombre') }}" type="text" required
                autocomplete="given-name" />

            <!-- Apellidos -->
            <flux:input name="apellidos" label="Apellidos" value="{{ old('apellidos') }}" type="text" required
                autocomplete="family-name" />

            <!-- Nombre de usuario -->
            <flux:input name="nombre_usuario" label="Nombre de usuario" value="{{ old('nombre_usuario') }}"
                type="text" required autocomplete="username" />

            <!-- Email -->
            <flux:input name="email" label="Email" value="{{ old('email') }}" type="email" required
                autocomplete="email" />

            <!-- Teléfono -->
            <flux:input name="telefono" label="Teléfono" value="{{ old('telefono') }}" type="text"
                autocomplete="tel" />

            <!-- Fecha de nacimiento -->
            <flux:input name="fecha_nacimiento" label="Fecha de nacimiento" type="date"
                value="{{ old('fecha_nacimiento') }}" required />

            <!-- Tipo de usuario -->
            <flux:select name="tipo" label="Tipo de usuario" required>
                <option value="">Selecciona un tipo</option>
                <option value="0">Cliente</option>
                <option value="1">Taller</option>
            </flux:select>

            <!-- Password -->
            <flux:input name="password" label="Contraseña" type="password" required autocomplete="new-password"
                viewable />

            <!-- Confirm Password -->
            <flux:input name="password_confirmation" label="Confirmar contraseña" type="password" required
                autocomplete="new-password" viewable />

            <div class="flex items-center justify-end">
                <flux:button type="submit" variant="primary" class="w-full">
                    Crear cuenta
                </flux:button>
            </div>
        </form>

        <div class="text-center text-sm text-zinc-600 dark:text-zinc-400">
            <span>¿Ya tienes cuenta?</span>
            <flux:link :href="route('login')" wire:navigate>Inicia sesión</flux:link>
        </div>
    </div>
</x-layouts::auth>
