@props([
'sidebar' => false,
])

@if($sidebar)
    <flux:sidebar.brand name="ReservApp" {{ $attributes }}>
    </flux:sidebar.brand>
@else
    <flux:brand name="ReservApp" {{ $attributes }}>
    </flux:brand>
@endif
