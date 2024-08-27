
document.addEventListener('DOMContentLoaded', function() {
    let currentCategory = 'guitarra'; // Categoría predeterminada
    let currentModel = '1'; // Modelo predeterminado

    const canvas = document.getElementById('canvas');
    const context = canvas.getContext('2d');
    const imageElement = new Image();

    function updateModelImage() {
        imageElement.src = `imgmodelos/modelo${currentModel}-${currentCategory}.png`;
        imageElement.onload = function() {
            canvas.width = imageElement.width;
            canvas.height = imageElement.height;
            context.drawImage(imageElement, 0, 0);
        };
    }

    function recolorImage(color) {
        context.clearRect(0, 0, canvas.width, canvas.height);
        context.drawImage(imageElement, 0, 0);

        context.globalCompositeOperation = 'source-in';
        context.fillStyle = color;
        context.fillRect(0, 0, canvas.width, canvas.height);
        context.globalCompositeOperation = 'destination-atop';
    }

    function handleColorChange(event) {
        const color = event.target.style.backgroundColor;
        recolorImage(color);
    }

    // Event Listeners para seleccionar categoría
    document.querySelectorAll('.categorias ul li').forEach(item => {
        item.addEventListener('click', function() {
            currentCategory = this.dataset.category; // Actualiza la categoría actual
            updateModelImage(); // Actualiza la imagen según la nueva categoría
        });
    });

    // Event Listeners para seleccionar modelo
    document.querySelectorAll('.modelos ul li').forEach(item => {
        item.addEventListener('click', function() {
            currentModel = this.dataset.model; // Actualiza el modelo actual
            updateModelImage(); // Actualiza la imagen según el nuevo modelo
        });
    });

    // Event Listeners para cambiar color
    document.querySelectorAll('.color').forEach(item => {
        item.addEventListener('click', handleColorChange);
    });

    // Listener para el botón limpiar
    document.querySelector('.limpiar').addEventListener('click', function() {
        const color = '#FFFFFF'; // Color blanco para "limpiar"
        recolorImage(color);
    });

    // Cargar imagen inicial
    updateModelImage();
});