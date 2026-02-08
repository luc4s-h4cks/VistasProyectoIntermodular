// ==========================================
// MODALES DE AUTENTICACIÓN
// ==========================================

// Mostrar un modal específico y ocultar los demás
function mostrarModal(modalId) {
    // Ocultar todos los modales de auth
    const modales = ['modalLogin', 'modalRegistro', 'modalRecuperarPassword', 'modalCodigoVerificacion', 'modalCambiarPassword'];
    modales.forEach(id => {
        const modal = document.getElementById(id);
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    });

    // Mostrar el modal solicitado
    const modalObjetivo = document.getElementById(modalId);
    if (modalObjetivo) {
        modalObjetivo.classList.remove('hidden');
        modalObjetivo.classList.add('flex');
    }
}

// Cerrar todos los modales de auth
function cerrarModalesAuth() {
    const modales = ['modalLogin', 'modalRegistro', 'modalRecuperarPassword', 'modalCodigoVerificacion', 'modalCambiarPassword'];
    modales.forEach(id => {
        const modal = document.getElementById(id);
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    });
}

// Abrir modal de inicio de sesión
function abrirLogin() {
    mostrarModal('modalLogin');
}

// Abrir modal de registro
function abrirRegistro() {
    mostrarModal('modalRegistro');
}

// Abrir modal de recuperar contraseña
function abrirRecuperarPassword() {
    mostrarModal('modalRecuperarPassword');
}

// Abrir modal de código de verificación
function abrirCodigoVerificacion() {
    mostrarModal('modalCodigoVerificacion');
}

// Abrir modal de cambiar contraseña
function abrirCambiarPassword() {
    mostrarModal('modalCambiarPassword');
}

// ==========================================
// LÓGICA DE FORMULARIOS
// ==========================================

// Enviar formulario de login
function enviarLogin(e) {
    e.preventDefault();
    const usuario = document.getElementById('loginUsuario').value;
    const password = document.getElementById('loginPassword').value;

    if (!usuario || !password) {
        alert('Por favor, rellena todos los campos.');
        return;
    }

    // Aquí iría la lógica de autenticación (fetch al backend)
    console.log('Login:', { usuario, password });
    alert('Inicio de sesión enviado (simulado)');
    cerrarModalesAuth();
}

// Enviar formulario de registro
function enviarRegistro(e) {
    e.preventDefault();
    const nombre = document.getElementById('registroNombre').value;
    const password = document.getElementById('registroPassword').value;
    const passwordRepetir = document.getElementById('registroPasswordRepetir').value;
    const email = document.getElementById('registroEmail').value;
    const esTaller = document.getElementById('registroEsTaller').checked;

    if (!nombre || !password || !passwordRepetir || !email) {
        alert('Por favor, rellena todos los campos.');
        return;
    }

    if (password !== passwordRepetir) {
        alert('Las contraseñas no coinciden.');
        return;
    }

    // Aquí iría la lógica de registro (fetch al backend)
    console.log('Registro:', { nombre, password, email, esTaller });
    alert('Registro enviado (simulado)');
    cerrarModalesAuth();
}

// Enviar formulario de recuperar contraseña
function enviarRecuperarPassword(e) {
    e.preventDefault();
    const usuario = document.getElementById('recuperarUsuario').value;

    if (!usuario) {
        alert('Por favor, introduce tu usuario o email.');
        return;
    }

    // Aquí iría la lógica de enviar código (fetch al backend)
    console.log('Recuperar password:', { usuario });
    abrirCodigoVerificacion();
}

// Enviar código de verificación
function enviarCodigoVerificacion(e) {
    e.preventDefault();
    const codigo = document.getElementById('codigoVerificacion').value;

    if (!codigo) {
        alert('Por favor, introduce el código de verificación.');
        return;
    }

    // Aquí iría la lógica de verificar código (fetch al backend)
    console.log('Código verificación:', { codigo });
    abrirCambiarPassword();
}

// Enviar formulario de cambiar contraseña
function enviarCambiarPassword(e) {
    e.preventDefault();
    const passwordNueva = document.getElementById('cambiarPasswordNueva').value;
    const passwordRepetir = document.getElementById('cambiarPasswordRepetir').value;

    if (!passwordNueva || !passwordRepetir) {
        alert('Por favor, rellena todos los campos.');
        return;
    }

    if (passwordNueva !== passwordRepetir) {
        alert('Las contraseñas no coinciden.');
        return;
    }

    // Aquí iría la lógica de cambiar contraseña (fetch al backend)
    console.log('Cambiar password:', { passwordNueva });
    alert('Contraseña cambiada (simulado)');
    cerrarModalesAuth();
}

// ==========================================
// AUTO-FOCUS EN INPUTS DE CÓDIGO
// ==========================================

function moverFocoCodigo(input, posicion) {
    if (input.value.length === 1 && posicion < 6) {
        const siguiente = document.getElementById('codigo' + (posicion + 1));
        if (siguiente) siguiente.focus();
    }
}

// Recoger valor completo del código
function obtenerCodigoCompleto() {
    let codigo = '';
    for (let i = 1; i <= 6; i++) {
        const input = document.getElementById('codigo' + i);
        if (input) codigo += input.value;
    }
    document.getElementById('codigoVerificacion').value = codigo;
}

// Cerrar modal si se hace click fuera del contenido
document.addEventListener('click', function (e) {
    const modales = ['modalLogin', 'modalRegistro', 'modalRecuperarPassword', 'modalCodigoVerificacion', 'modalCambiarPassword'];
    modales.forEach(id => {
        const modal = document.getElementById(id);
        if (modal && e.target === modal) {
            cerrarModalesAuth();
        }
    });
});
