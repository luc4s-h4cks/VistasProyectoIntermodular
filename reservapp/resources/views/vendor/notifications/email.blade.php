<x-mail::message>
@if(str_contains($actionUrl ?? '', 'reset-password'))
# 🔐 Restablece tu contraseña
@else
# ✉ Verifica tu correo electrónico
@endif

@foreach ($introLines as $line)
{{ $line }}

@endforeach

@if(str_contains($actionUrl ?? '', 'reset-password'))
<x-mail::panel>
⏱ **Este enlace expira pronto.** Por seguridad este enlace tiene un tiempo de validez limitado.
</x-mail::panel>
@else
<x-mail::panel>
🔒 **¿Por qué verificar tu correo?** Verificar tu dirección nos ayuda a mantener tu cuenta segura.
</x-mail::panel>
@endif

@isset($actionText)
<?php
$color = match ($level) {
'success', 'error' => $level,
default => 'primary',
};
?>
<x-mail::button :url="$actionUrl" :color="$color">
{{ $actionText }}
</x-mail::button>
@endisset

@if(str_contains($actionUrl ?? '', 'reset-password'))
<x-mail::panel>
⚠ **¿No solicitaste este cambio?** Si no fuiste tú, ignora este correo. Tu contraseña actual seguirá siendo la misma.
</x-mail::panel>
@endif

@foreach ($outroLines as $line)
{{ $line }}

@endforeach

@if (! empty($salutation))
{{ $salutation }}
@else
@lang('Regards,')<br>
{{ config('app.name') }}
@endif

@isset($actionText)
<x-slot:subcopy>
@lang(
"If you're having trouble clicking the \":actionText\" button, copy and paste the URL below\n".
'into your web browser:',
[
'actionText' => $actionText,
]
) <span class="break-all">[{{ $displayableActionUrl }}]({{ $actionUrl }})</span>
</x-slot:subcopy>
@endisset
</x-mail::message>
