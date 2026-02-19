{{--
    Vista principal de configuración de perfil de usuario

    Esta vista centraliza TODAS las configuraciones del usuario en una sola página:

    SECCIONES:
    1. Información básica (nombre, apellidos, teléfono, fecha nacimiento)
       - Siempre editables, guardar directo

    2. Datos sensibles (email, nombre de usuario)
       - Deshabilitados por defecto
       - Botón "Modificar" para habilitar edición
       - Requiere contraseña actual para guardar
       - Botones "Guardar" y "Cancelar" al editar

    3. Cambiar contraseña
       - Botón "Cambiar contraseña" para habilitar
       - Campos: contraseña actual, nueva, confirmar nueva
       - Botones "Guardar" y "Cancelar" al editar

    4. Apariencia
       - Selector de tema: Claro / Oscuro / Sistema
       - Manejado por Flux UI con Alpine.js (sin backend)

    5. Autenticación de dos factores
       - Subcomponente: <livewire:settings.two-factor />
       - Habilitar/deshabilitar 2FA con modal de configuración

    6. Eliminar cuenta
       - Solo si el usuario está verificado
       - Subcomponente: <livewire:settings.delete-user-form />

    Lógica backend: App\Livewire\Settings\Profile.php
--}}
<section class="w-full">
    @include('partials.settings-heading')

    <flux:heading class="sr-only">{{ __('Profile Settings') }}</flux:heading>

    <x-settings.layout :heading="__('Perfil')" :subheading="__('Modifica tu información de perfil.')">

        {{-- Basic Profile Information --}}
        <form wire:submit="updateBasicInformation" class="my-6 w-full space-y-6">
            <flux:input
                wire:model="nombre"
                :label="__('Nombre')"
                type="text"
                required
                autofocus
                autocomplete="given-name"
            />

            <flux:input
                wire:model="apellidos"
                :label="__('Apellidos')"
                type="text"
                required
                autocomplete="family-name"
            />

            <flux:input
                wire:model="telefono"
                :label="__('Teléfono')"
                type="tel"
                autocomplete="tel"
            />

            <flux:input
                wire:model="fecha_nacimiento"
                :label="__('Fecha de nacimiento')"
                type="date"
                required
            />

            {{-- Profile Image Section --}}
            <div class="space-y-3">
                <flux:label>{{ __('Foto de perfil') }}</flux:label>

                {{-- Preview nueva imagen (tiene prioridad) --}}
                @if($img_perfil)
                    <div class="mb-4 rounded-lg border border-zinc-200 p-4 dark:border-zinc-700">
                        <img
                            src="{{ $img_perfil->temporaryUrl() }}"
                            alt="{{ __('Vista previa de foto de perfil') }}"
                            class="h-32 w-32 rounded-lg object-cover"
                        />
                        <flux:text class="mt-3 text-sm">{{ __('Vista previa') }}</flux:text>
                    </div>

                {{-- Imagen guardada actual --}}
                @elseif($this->currentImageUrl)
                    <div class="mb-4 rounded-lg border border-zinc-200 p-4 dark:border-zinc-700">
                        <img
                            src="{{ $this->currentImageUrl }}"
                            alt="{{ __('Foto de perfil actual') }}"
                            class="h-32 w-32 rounded-lg object-cover"
                        />
                        <flux:text class="mt-3 text-sm">{{ __('Foto actual') }}</flux:text>
                    </div>
                @endif

                {{-- File Input --}}
                <flux:input
                    wire:model="img_perfil"
                    type="file"
                    accept="image/jpeg,image/png,image/webp"
                    :hint="__('Máximo 5 MB. Formatos permitidos: JPG, PNG, WebP')"
                />
            </div>

            <div class="flex items-center gap-4">
                <flux:button
                    variant="primary"
                    type="submit"
                    wire:loading.attr="disabled"
                    wire:target="img_perfil"
                >
                    <span wire:loading.remove wire:target="img_perfil">{{ __('Guardar cambios') }}</span>
                    <span wire:loading wire:target="img_perfil">{{ __('Subiendo imagen...') }}</span>
                </flux:button>

                <x-action-message class="me-3" on="profile-updated">
                    {{ __('Guardado.') }}
                </x-action-message>
            </div>
        </form>

        <flux:separator class="my-8" />

        {{-- Sensitive Data (Email, Username) --}}
        <div class="my-6 w-full space-y-6">
            <flux:heading size="lg">{{ __('Datos sensibles') }}</flux:heading>
            <flux:subheading>{{ __('Modifica tu email y nombre de usuario. Requiere contraseña para confirmar cambios.') }}</flux:subheading>

            <form wire:submit="updateSensitiveData" class="space-y-6">
                <flux:input
                    wire:model="email"
                    :label="__('Email')"
                    type="email"
                    required
                    autocomplete="email"
                    :disabled="!$editingSensitiveData"
                />

                @if ($this->hasUnverifiedEmail)
                    <div>
                        <flux:text class="mt-2">
                            {{ __('Your email address is unverified.') }}

                            <flux:link class="text-sm cursor-pointer" wire:click.prevent="resendVerificationNotification">
                                {{ __('Click here to re-send the verification email.') }}
                            </flux:link>
                        </flux:text>

                        @if (session('status') === 'verification-link-sent')
                            <flux:text class="mt-2 font-medium !dark:text-green-400 !text-green-600">
                                {{ __('A new verification link has been sent to your email address.') }}
                            </flux:text>
                        @endif
                    </div>
                @endif

                <flux:input
                    wire:model="nombre_usuario"
                    :label="__('Nombre de usuario')"
                    type="text"
                    required
                    autocomplete="username"
                    :disabled="!$editingSensitiveData"
                />

                @if ($editingSensitiveData)
                    <flux:input
                        wire:model="current_password"
                        :label="__('Contraseña actual')"
                        type="password"
                        required
                        autocomplete="current-password"
                        :description="__('Confirma tu contraseña para actualizar estos datos.')"
                    />
                @endif

                <div class="flex items-center gap-4">
                    @if (!$editingSensitiveData)
                        <flux:button
                            variant="primary"
                            type="button"
                            wire:click="enableSensitiveDataEditing"
                        >
                            {{ __('Modificar') }}
                        </flux:button>
                    @else
                        <flux:button variant="primary" type="submit">
                            {{ __('Guardar') }}
                        </flux:button>

                        <flux:button
                            variant="ghost"
                            type="button"
                            wire:click="cancelSensitiveDataEditing"
                        >
                            {{ __('Cancelar') }}
                        </flux:button>
                    @endif

                    <x-action-message class="me-3" on="sensitive-data-updated">
                        {{ __('Guardado.') }}
                    </x-action-message>
                </div>
            </form>
        </div>

        <flux:separator class="my-8" />

        {{-- Change Password --}}
        <div class="my-6 w-full space-y-6">
            <flux:heading size="lg">{{ __('Cambiar contraseña') }}</flux:heading>
            <flux:subheading>{{ __('Actualiza tu contraseña para mantener tu cuenta segura.') }}</flux:subheading>

            <form wire:submit="updatePassword" class="space-y-6">
                @if ($editingPassword)
                    <flux:input
                        wire:model="password_actual"
                        :label="__('Contraseña actual')"
                        type="password"
                        required
                        autocomplete="current-password"
                    />

                    <flux:input
                        wire:model="password"
                        :label="__('Nueva contraseña')"
                        type="password"
                        required
                        autocomplete="new-password"
                    />

                    <flux:input
                        wire:model="password_confirmation"
                        :label="__('Confirmar nueva contraseña')"
                        type="password"
                        required
                        autocomplete="new-password"
                    />
                @endif

                <div class="flex items-center gap-4">
                    @if (!$editingPassword)
                        <flux:button
                            variant="primary"
                            type="button"
                            wire:click="enablePasswordEditing"
                        >
                            {{ __('Cambiar contraseña') }}
                        </flux:button>
                    @else
                        <flux:button variant="primary" type="submit">
                            {{ __('Guardar') }}
                        </flux:button>

                        <flux:button
                            variant="ghost"
                            type="button"
                            wire:click="cancelPasswordEditing"
                        >
                            {{ __('Cancelar') }}
                        </flux:button>
                    @endif

                    <x-action-message class="me-3" on="password-updated">
                        {{ __('Contraseña actualizada.') }}
                    </x-action-message>
                </div>
            </form>
        </div>

        <flux:separator class="my-8" />

        {{-- Appearance Settings --}}
        <div class="my-6 w-full space-y-6">
            <flux:heading size="lg">{{ __('Apariencia') }}</flux:heading>
            <flux:subheading>{{ __('Personaliza el tema de la aplicación según tu preferencia.') }}</flux:subheading>

            <flux:radio.group x-data variant="segmented" x-model="$flux.appearance">
                <flux:radio value="light" icon="sun">{{ __('Claro') }}</flux:radio>
                <flux:radio value="dark" icon="moon">{{ __('Oscuro') }}</flux:radio>
                <flux:radio value="system" icon="computer-desktop">{{ __('Sistema') }}</flux:radio>
            </flux:radio.group>
        </div>

        @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
            <flux:separator class="my-8" />

            {{-- Two-Factor Authentication --}}
            <div class="my-6 w-full space-y-6">
                <flux:heading size="lg">{{ __('Autenticación de dos factores') }}</flux:heading>
                <flux:subheading>{{ __('Agrega una capa adicional de seguridad a tu cuenta.') }}</flux:subheading>

                <livewire:settings.two-factor />
            </div>
        @endif

        @if ($this->showDeleteUser)
            <flux:separator class="my-8" />
            <livewire:settings.delete-user-form />
        @endif
    </x-settings.layout>
</section>
