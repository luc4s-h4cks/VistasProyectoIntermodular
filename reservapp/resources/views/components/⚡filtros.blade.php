<?php

use Livewire\Component;

new class extends Component
{
    //
};
?>

<div>
    <h1> Filtros </h1>
    <h2> Tipo de vehículo </h2>
    <flux:select placeholder="Selecciona un tipo de vehículo" clearable>
        <flux:select.option value="coche">Coche</flux:select.option>
        <flux:select.option value="moto">Moto</flux:select.option>
        <flux:select.option value="camion">Camión</flux:select.option>
    </flux:select>

    <flux:select placeholder="Tipo de servicio" clearable>
        <flux:select.option value="mecanica">Mecánica</flux:select.option>
        <flux:select.option value="electricidad">Electricidad</flux:select.option>
        <flux:select.option value="neumaticos">Neumáticos</flux:select.option>
    </flux:select>
</div>
