<?php

use Livewire\Component;
use App\Models\Cita;
use App\Models\Taller;
use App\Models\Dia;

new class extends Component {
    public $cars = [];
    public $tallerId;
    public $cocheId = '';
    public $tramoHorario = '';
    public $motivo = '';
    public $fecha = '';
    public $dia_no_disponible = [];

    public function mount(Taller $taller)
    {
        $this->tallerId = $taller->id_taller;
        $this->cars = auth()->user()->coches()->get();
        $this->dia_no_disponible = Dia::where('id_taller', $this->tallerId)
            ->where('estado', Dia::ESTADO_OCUPADO)
            ->pluck('fecha')
            ->toArray();
    }
    public function enviar(){
        $this->validate([
            'cocheId' => 'required',
            'fecha' => 'required|date|after_or_equal:today',
            'tramoHorario' => 'required',
            'motivo' => 'required',
        ]);

        $dia= Dia::where('id_taller', $this->tallerId)
            ->where('fecha', $this->fecha)
            ->first();
        if (!$dia)
        //crea el dia en el calendario del taller
        {
            $dia = new Dia();
            $dia->id_taller = $this->tallerId;
            $dia->fecha = $this->fecha;
            $dia->estado= Dia::ESTADO_LIBRE;
            $dia->save();
        }
        $cita = new Cita();
        $cita->fecha = $dia->fecha;
        $cita->id_taller = $this->tallerId;
        $cita->id_coche = $this->cocheId;
        $cita->id_usuario = auth()->id();
        $cita->tramo_horario = $this->tramoHorario;
        $cita->motivo = $this->motivo;
        $cita->estado= Cita::ESTADO_SOLICITADO;
        $cita->save();

        $this->reset(['cocheId', 'tramoHorario', 'motivo', 'fecha']);
        $this->dispatch('cita-guardada');
    }
};
?>


<div>
<div class="bg-background dark:bg-zinc-800 rounded-2xl shadow-md p-6">
    <h2 class="text-xl font-semibold text-text dark:text-zinc-100 border-b-2 border-secondary pb-2 mb-4">
        Calendario de citas
    </h2>

    <div class="bg-zinc-50 dark:bg-zinc-700 p-4 rounded-xl" wire:ignore>
        {{-- Navegación mes --}}
        <div class="flex items-center justify-between mb-4">
            <button id="prevMonth" class="px-4 py-2 bg-primary text-white rounded-lg hover:opacity-90 transition-opacity text-sm font-medium">
                &larr; Anterior
            </button>
            <h4 id="mesAnio" class="font-semibold text-lg text-text dark:text-zinc-100"></h4>
            <button id="nextMonth" class="px-4 py-2 bg-primary text-white rounded-lg hover:opacity-90 transition-opacity text-sm font-medium">
                Siguiente &rarr;
            </button>
        </div>

        {{-- Días de la semana --}}
        <div class="grid grid-cols-7 gap-2 text-center font-semibold text-sm text-zinc-500 dark:text-zinc-400 mb-2">
            <div class="p-2">L</div>
            <div class="p-2">M</div>
            <div class="p-2">X</div>
            <div class="p-2">J</div>
            <div class="p-2">V</div>
            <div class="p-2">S</div>
            <div class="p-2">D</div>
        </div>

        {{-- Días del mes (generados por JS) --}}
        <div id="diasMes" class="grid grid-cols-7 gap-2 text-center"></div>
    </div>
</div>

{{-- Modal Pedir Cita --}}
<div id="modalPedirCita" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 transition-opacity duration-300 opacity-0">
    <div id="modalContenido" class="bg-background dark:bg-zinc-800 rounded-2xl p-6 w-[90vw] max-w-md shadow-xl transform transition-all duration-300 scale-95 opacity-0 relative">
        <button onclick="cerrarModalCita()" class="absolute top-3 right-4 text-zinc-400 hover:text-text dark:hover:text-zinc-100 text-2xl font-bold">&times;</button>

        <h2 class="text-2xl font-bold text-center text-primary mb-2">Pedir Cita</h2>
        <h3 id="diaSeleccionado" class="text-lg text-center text-zinc-500 dark:text-zinc-400 mb-6"></h3>

        <form id="formPedirCita" class="space-y-4" wire:submit.prevent="enviar">
            <input type="hidden" id="fechaInput" wire:model.defer="fecha">
            <div>
                <flux:label>Vehículo</flux:label>
                <flux:select wire:model="cocheId">
                    <option value="">Selecciona tu vehículo</option>
                    @foreach($cars as $car)
                        <option value="{{ $car->id_coche }}">{{ $car->marca }} {{ $car->modelo }}</option>
                    @endforeach
                </flux:select>
            </div>

            <div>
                <flux:label>Tramo horario</flux:label>
                <flux:select wire:model="tramoHorario">
                    <option value="">Selecciona un tramo</option>
                    <option value="manana">Mañana</option>
                    <option value="tarde">Tarde</option>
                </flux:select>
            </div>

            <div>
                <flux:label>Motivo de la cita</flux:label>
                <flux:textarea wire:model="motivo" placeholder="Describe el motivo de tu cita..." rows="3" />
            </div>

            <div class="text-center pt-2">
                <flux:button type="submit" variant="primary" class="w-full">
                    Solicitar cita
                </flux:button>
            </div>
        </form>
    </div>
</div>

<script>
    // ── Calendario ──
    let mesActual = new Date().getMonth();
    let anioActual = new Date().getFullYear();

    const nombresMeses = [
        'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
        'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
    ];

    function generarCalendario(mes, anio) {
        const diasMesContainer = document.getElementById('diasMes');
        const mesAnioTitulo = document.getElementById('mesAnio');

        mesAnioTitulo.textContent = `${nombresMeses[mes]} ${anio}`;
        diasMesContainer.innerHTML = '';

        const primerDia = new Date(anio, mes, 1).getDay();
        const diasEnMes = new Date(anio, mes + 1, 0).getDate();
        const ajustePrimerDia = primerDia === 0 ? 6 : primerDia - 1;

        for (let i = 0; i < ajustePrimerDia; i++) {
            const celdaVacia = document.createElement('div');
            celdaVacia.className = 'p-2';
            diasMesContainer.appendChild(celdaVacia);
        }

        const hoy = new Date();
        const diaHoy = hoy.getDate();
        const mesHoy = hoy.getMonth();
        const anioHoy = hoy.getFullYear();

        for (let dia = 1; dia <= diasEnMes; dia++) {
            const celdaDia = document.createElement('div');
            const esPasado = new Date(anio, mes, dia) < new Date(anioHoy, mesHoy, diaHoy);

            if (esPasado) {
                celdaDia.className = 'p-2 rounded-lg text-zinc-300 dark:text-zinc-600 cursor-not-allowed';
            } else if (dia === diaHoy && mes === mesHoy && anio === anioHoy) {
                celdaDia.className = 'p-2 rounded-lg cursor-pointer bg-primary text-white font-bold shadow-sm';
                celdaDia.addEventListener('click', () => abrirModalCita(dia, mes));
            } else {
                celdaDia.className = 'p-2 rounded-lg cursor-pointer transition-colors text-text dark:text-zinc-200 hover:bg-primary/10 dark:hover:bg-primary/20';
                celdaDia.addEventListener('click', () => abrirModalCita(dia, mes));
            }

            celdaDia.textContent = dia;
            diasMesContainer.appendChild(celdaDia);
        }
    }

    // ── Modal Pedir Cita ──
    function abrirModalCita(dia, mes) {
        const modal = document.getElementById('modalPedirCita');
        const contenido = document.getElementById('modalContenido');
        document.getElementById('diaSeleccionado').textContent = `${dia} de ${nombresMeses[mes]}`;

        const fechaFormateada = `${anioActual}-${String(mes + 1).padStart(2, '0')}-${String(dia).padStart(2, '0')}`;
        const fechaInput = document.getElementById('fechaInput');
        fechaInput.value = fechaFormateada;
        fechaInput.dispatchEvent(new Event('input'));

        modal.classList.remove('hidden');
        modal.classList.add('flex');

        setTimeout(() => {
            modal.classList.remove('opacity-0');
            modal.classList.add('opacity-100');
            contenido.classList.remove('scale-95', 'opacity-0');
            contenido.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function cerrarModalCita() {
        const modal = document.getElementById('modalPedirCita');
        const contenido = document.getElementById('modalContenido');

        modal.classList.remove('opacity-100');
        modal.classList.add('opacity-0');
        contenido.classList.remove('scale-100', 'opacity-100');
        contenido.classList.add('scale-95', 'opacity-0');

        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 300);
    }

    document.getElementById('modalPedirCita').addEventListener('click', (e) => {
        if (e.target.id === 'modalPedirCita') cerrarModalCita();
    });

    window.addEventListener('cita-guardada', () => {
        cerrarModalCita();
    });

    // ── Navegación meses ──
    document.getElementById('prevMonth').addEventListener('click', () => {
        mesActual--;
        if (mesActual < 0) { mesActual = 11; anioActual--; }
        generarCalendario(mesActual, anioActual);
    });

    document.getElementById('nextMonth').addEventListener('click', () => {
        mesActual++;
        if (mesActual > 11) { mesActual = 0; anioActual++; }
        generarCalendario(mesActual, anioActual);
    });

    // Inicializar
    generarCalendario(mesActual, anioActual);
</script>
</div>
