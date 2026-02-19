<x-layouts::app :title="__('Mi Taller')">

    <div class="flex w-full">

        <x-sidebar-taller />

        <div class="flex-1 p-6">

            <livewire:taller.taller-form :taller="$taller" />

        </div>

    </div>

</x-layouts::app>
