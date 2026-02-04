// Función para expandir/contraer filtros
function toggleFiltro(id) {
    const filtro = document.getElementById(id);
    
    // Construir el ID del icono correctamente
    let iconoId;
    if (id === 'filtroVehiculo') {
        iconoId = 'iconoVehiculo';
    } else if (id === 'filtroServicio') {
        iconoId = 'iconoServicio';
    } else if (id === 'filtroVehiculoModal') {
        iconoId = 'iconoVehiculoModal';
    } else if (id === 'filtroServicioModal') {
        iconoId = 'iconoServicioModal';
    }
    
    const icono = document.getElementById(iconoId);
    
    if (filtro.classList.contains('hidden')) {
        // Expandir
        filtro.classList.remove('hidden');
        icono.textContent = '-';
    } else {
        // Contraer
        filtro.classList.add('hidden');
        icono.textContent = '+';
    }
}

// Función para abrir el modal de filtros en móvil
function abrirFiltros() {
    const modal = document.getElementById('modalFiltros');
    const modalContenido = document.getElementById('modalFiltrosContenido');
    
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

// Función para cerrar el modal de filtros
function cerrarFiltros() {
    const modal = document.getElementById('modalFiltros');
    const modalContenido = document.getElementById('modalFiltrosContenido');
    
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

// Event listener para el botón de filtros móvil
document.getElementById('btnFiltrosMobile').addEventListener('click', abrirFiltros);

// Event listener para cerrar modal al hacer click fuera
document.getElementById('modalFiltros').addEventListener('click', (e) => {
    if (e.target.id === 'modalFiltros') {
        cerrarFiltros();
    }
});
