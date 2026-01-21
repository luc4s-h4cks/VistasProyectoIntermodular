const d = document;

d.addEventListener("click", (e) => {
  if (e.target.matches("#solicitudes")) {
    d.getElementById("content-solicitudes").classList.remove("hidden");
    d.getElementById("content-notificaciones").classList.add("hidden");
    d.getElementById("solicitudes").classList.add("bg-gray-300")
    d.getElementById("notificaciones").classList.remove("bg-gray-300")
    d.getElementById("notificaciones").classList.add("bg-gray-200")
  }

  if (e.target.matches("#notificaciones")) {
    d.getElementById("content-notificaciones").classList.remove("hidden");
    d.getElementById("content-solicitudes").classList.add("hidden");
    d.getElementById("solicitudes").classList.add("bg-gray-200")
    d.getElementById("solicitudes").classList.remove("bg-gray-300")
    d.getElementById("notificaciones").classList.add("bg-gray-300")
  }
});

function crearCalendario() {
  let calendario = d.getElementById("calendario");
  for (let i = 1; i < 31; i++) {
    let dia = d.createElement("button");
    dia.textContent = i;
    dia.id = "dia" + i;
    dia.classList.add("border");
    dia.classList.add("rounded");
    dia.classList.add("p-2");
    dia.classList.add("hover:bg-gray-200");
    dia.addEventListener("click", () => {
      mostrarModalDia(i);
    });
    calendario.appendChild(dia);
  }
}

function mostrarModalDia(dia) {
  const modal = d.getElementById("modalDia");

  // Actualiza el título del modal
  d.getElementById("tituloDia").textContent = "Día " + dia;

  // Muestra el modal
  modal.classList.remove("hidden");
  modal.classList.add("flex");
}

function mostrarModalSolicitud() {
  let modal = d.getElementById("modalSolicitud");

  modal.classList.remove("hidden");
  modal.classList.add("flex");
}

function mostrarCambioFecha() {
  let zona = d.getElementById("cambioFecha");

  zona.classList.remove("hidden");
}

function cerrarModalDia() {
  const modal = d.getElementById("modalDia");
  modal.classList.add("hidden");
  modal.classList.remove("flex");
}

function cerrarModalSolicitud() {
  const modal = d.getElementById("modalSolicitud");
  modal.classList.add("hidden");
  modal.classList.remove("flex");
}

d.addEventListener("DOMContentLoaded", (e) => {
  crearCalendario();

  let modalSolicitud = d.getElementById("modalSolicitud");
  modalSolicitud.addEventListener("click", (e) => {
    if (e.target === modalSolicitud) {
      modalSolicitud.classList.add("hidden");
    }
  });
});
