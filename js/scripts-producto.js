document.addEventListener("DOMContentLoaded", function() {
    var cuentaLink = document.getElementById("categorias-link");
    var opcionesCuenta = document.getElementById("opciones-cuenta");

    cuentaLink.addEventListener("click", function(event) {
        event.preventDefault();
        opcionesCuenta.style.display = opcionesCuenta.style.display === "none" ? "block" : "none";
    });
});