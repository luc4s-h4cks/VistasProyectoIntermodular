<?php

use Livewire\Component;

new class extends Component
{
    public $mostrar = false;
    public $tiposPropulsion = [];
    public ?Coche $car = null;


    public function mount(): void
    {
        $this->tiposPropulsion = TipoCombustible::all();
    }

    public function mostrarForm()
    {
        $this->mostrar = !$this->mostrar;
    }
};
?>

<div>
    <flux:button wire:click="mostrarForm" variant='primary'>Nuevo Coche</flux:button>
    @if($mostrar)
    <h3>Editar Coche</h3>
    <form method="POST" action="{{ route('cars.update', $car->id) }}" enctype="multipart/form-data" class="max-w-md mx-auto">
        @csrf
        @method('PUT')
        <div class="relative z-0 w-full mb-5 group">
            <input type="text" value="{{ old('matricula', $car->matricula) }}" name="matricula" id="matricula" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
            <label for="matricula" class="">Matrícula</label>
            @error('matricula')
                <div class="text-red-500 mt-2 text-sm">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="relative z-0 w-full mb-5 group">
            <input type="text" value="{{ old('marca', $car->marca) }}" name="marca" id="marca" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
            <label for="marca" class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Marca</label>
            @error('marca')
                <div class="text-red-500 mt-2 text-sm">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="relative z-0 w-full mb-5 group">
            <input type="text" value="{{ old('modelo', $car->modelo) }}" name="modelo" id="modelo" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
            <label for="modelo" class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Modelo</label>
            @error('modelo')
                <div class="text-red-500 mt-2 text-sm">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="relative z-0 w-full mb-5 group">
            <input type="text" value="{{ old('n_bastidor', $car->n_bastidor) }}" name="n_bastidor" id="n_bastidor" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
            <label for="n_bastidor" class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Número de bastidor</label>
            @error('n_bastidor')
                <div class="text-red-500 mt-2 text-sm">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="relative z-0 w-full mb-5 group">
            Tipo de Propulsión:
            <select name="tipo_conbustible" id="tipo_conbustible" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" required>
                <option value="" disabled selected>Selecciona el tipo de propulsión</option>
                @foreach($tiposPropulsion as $tipo)
                    <option value="{{ $tipo->tipo_combustible }}" {{ old('tipo_conbustible') == $tipo->tipo_combustible ? 'selected' : '' }}>{{ $tipo->nombre }}</option>
                @endforeach
            </select>
            @error('tipo_conbustible')
                <div class="text-red-500 mt-2 text-sm">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">
            Actualizar coche
        </button>
    </form>
    @endif
</div>
