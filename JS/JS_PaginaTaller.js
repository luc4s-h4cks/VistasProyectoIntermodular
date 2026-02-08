// Variables globales para el calendario
let mesActual = 0; // Enero
let anioActual = 2026;

// Nombres de los meses
const nombresMeses = [
    'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
    'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
];

// Función para generar el calendario
function generarCalendario(mes, anio) {
    const diasMesContainer = document.getElementById('diasMes');
    const mesAnioTitulo = document.getElementById('mesAnio');
    
    // Actualizar título
    mesAnioTitulo.textContent = `${nombresMeses[mes]} ${anio}`;
    
    // Limpiar días anteriores
    diasMesContainer.innerHTML = '';
    
    // Obtener primer día del mes (0 = domingo, 1 = lunes, etc.)
    const primerDia = new Date(anio, mes, 1).getDay();
    
    // Obtener número de días en el mes
    const diasEnMes = new Date(anio, mes + 1, 0).getDate();
    
    // Ajustar para que lunes sea el primer día (en lugar de domingo)
    const ajustePrimerDia = primerDia === 0 ? 6 : primerDia - 1;
    
    // Agregar celdas vacías antes del primer día
    for (let i = 0; i < ajustePrimerDia; i++) {
        const celdaVacia = document.createElement('div');
        celdaVacia.className = 'p-2';
        diasMesContainer.appendChild(celdaVacia);
    }
    
    // Obtener fecha actual
    const hoy = new Date();
    const diaHoy = hoy.getDate();
    const mesHoy = hoy.getMonth();
    const anioHoy = hoy.getFullYear();
    
    // Agregar días del mes
    for (let dia = 1; dia <= diasEnMes; dia++) {
        const celdaDia = document.createElement('div');
        celdaDia.className = 'p-2 border hover:bg-gray-100 cursor-pointer rounded';
        celdaDia.textContent = dia;
        
        // Resaltar el día actual
        if (dia === diaHoy && mes === mesHoy && anio === anioHoy) {
            celdaDia.classList.add('bg-blue-500', 'text-white', 'font-bold');
            celdaDia.classList.remove('hover:bg-gray-100');
        }
        
        // Agregar evento click para abrir modal
        celdaDia.addEventListener('click', () => {
            abrirModalCita(dia, mes);
        });
        
        diasMesContainer.appendChild(celdaDia);
    }
}

// Función para abrir el modal de pedir cita
function abrirModalCita(dia, mes) {
    const modal = document.getElementById('modalPedirCita');
    const modalContenido = document.getElementById('modalContenido');
    const diaSeleccionado = document.getElementById('diaSeleccionado');
    
    // Actualizar el título con el día y mes seleccionado
    diaSeleccionado.textContent = `Día: ${dia} ${nombresMeses[mes]}`;
    
    // Mostrar el modal
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    
    // Activar animación después de un pequeño delay
    setTimeout(() => {
        modal.classList.remove('opacity-0');
        modal.classList.add('opacity-100');
        modalContenido.classList.remove('scale-95', 'opacity-0');
        modalContenido.classList.add('scale-100', 'opacity-100');
    }, 10);
}

// Función para cerrar el modal
function cerrarModalCita() {
    const modal = document.getElementById('modalPedirCita');
    const modalContenido = document.getElementById('modalContenido');
    
    // Animar el cierre
    modal.classList.remove('opacity-100');
    modal.classList.add('opacity-0');
    modalContenido.classList.remove('scale-100', 'opacity-100');
    modalContenido.classList.add('scale-95', 'opacity-0');
    
    // Ocultar después de la animación
    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }, 300);
}

// Event listener para el formulario
document.getElementById('formPedirCita').addEventListener('submit', (e) => {
    e.preventDefault();
    
    // Aquí puedes agregar la lógica para enviar la solicitud
    alert('Solicitud de cita enviada correctamente!');
    cerrarModalCita();
    
    // Limpiar el formulario
    e.target.reset();
});

// Event listener para cerrar modal al hacer click fuera
document.getElementById('modalPedirCita').addEventListener('click', (e) => {
    // Si el click fue en el overlay (no en el contenido), cerrar el modal
    if (e.target.id === 'modalPedirCita') {
        cerrarModalCita();
    }
});

// Función para ir al mes anterior
function mesAnterior() {
    mesActual--;
    if (mesActual < 0) {
        mesActual = 11;
        anioActual--;
    }
    generarCalendario(mesActual, anioActual);
}

// Función para ir al mes siguiente
function mesSiguiente() {
    mesActual++;
    if (mesActual > 11) {
        mesActual = 0;
        anioActual++;
    }
    generarCalendario(mesActual, anioActual);
}

// Event listeners para los botones
document.getElementById('prevMonth').addEventListener('click', mesAnterior);
document.getElementById('nextMonth').addEventListener('click', mesSiguiente);

// Generar calendario inicial al cargar la página
generarCalendario(mesActual, anioActual);
