const d = document

d.addEventListener("click", (e) => {
    if (e.target.matches('#solicitudes')) {
        d.getElementById("content-solicitudes").classList.remove("hidden")
        d.getElementById("content-notificaciones").classList.add("hidden")
    }

    if (e.target.matches('#notificaciones')) {
        d.getElementById("content-notificaciones").classList.remove("hidden")
        d.getElementById("content-solicitudes").classList.add("hidden")
    }
})

function crearCalendario() {
    console.log("hola")
    let calendario = d.getElementById("calendario")
    for (let i = 1; i < 31; i++) {
        let dia = d.createElement("button")
        dia.textContent = i
        dia.id = "dia" + i
        dia.classList.add("border")
        dia.classList.add("rounded")
        dia.classList.add("p-2")
        dia.classList.add("hover:bg-gray-200")
        dia.addEventListener("click", ()=>{
            mostrarModalDia(i)
        })
        calendario.appendChild(dia)
    }

}

function mostrarModalDia(dia){
    d.getElementById("modalDiaText").textContent = "Se a selecionado el dia "+dia
    d.getElementById("modalDia").classList.remove("hidden")
    d.getElementById("modalDia").classList.add("flex")
}

function cerrarModalDia(dia){
    d.getElementById("modalDia").classList.add("hidden")
    d.getElementById("modalDia").classList.remove("flex")
}

d.addEventListener("DOMContentLoaded", (e) => {
    crearCalendario();
})