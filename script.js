// Simple script para mostrar una alerta al hacer clic en un evento
document.querySelectorAll('.evento').forEach(evento => {
    evento.addEventListener('click', () => {
        alert("Has seleccionado un evento.");
    });
});
