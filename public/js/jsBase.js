// -------- SWEETALERT CON LIVEWIRE --------
document.addEventListener('livewire:init', () => {
    Livewire.on('sweetalert', (data) => {
        if (Array.isArray(data) && data.length === 1 && typeof data[0] === 'object') {
            data = data[0];
        }

        Swal.fire({
            icon: data.icon ?? 'success',
            title: data.text ?? '',
            showConfirmButton: false,
            timer: data.timer ?? 10000,
            timerProgressBar: true,
            target: document.body,
            customClass: {
                container: 'modalSweet'
            }
        });
    });
});
//msgBox
document.addEventListener('DOMContentLoaded', () => {
    window.addEventListener('msgBox', event => {
        const { titulo, contenido } = event.detail;

        alert(
            titulo + "\n\n" + contenido
        );
    });
});
// VER PASSWORD
function togglePassword() {
    const passwordInput = document.getElementById('password');
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
    } else {
        passwordInput.type = 'password';
    }
}
//Cambiar tab
window.addEventListener('activarTab', (event) => {
    const tabId = event.detail.tab;
    const label = document.querySelector(`label[for="${tabId}"]`);
    if (label) label.click();
});
//Zoom de imagen
function verZoom(src, nombre) {
    const imagenModal = new bootstrap.Modal(document.getElementById('imagenModal'));
    const imagenAmpliada = document.getElementById('imagenAmpliada');
    const imagenModalLabel = document.getElementById('imagenModalLabel');

    imagenAmpliada.src = src;
    imagenAmpliada.alt = nombre;
    imagenModalLabel.textContent = nombre;
    imagenModal.show();
}

//Actualizar lista para SELECT
document.addEventListener('livewire:init', () => {
    Livewire.on('actualizarLista', data => {
        const { arrayActual, campo } = data;
        const select = document.querySelector(`select[wire\\:model="${campo}"]`);
        if (!select) return;
        select.innerHTML = '<option value=""></option>';
        for (const id in arrayActual) {
            const opt = document.createElement('option');
            opt.value = id;
            opt.textContent = arrayActual[id];
            select.appendChild(opt);
        }
    });
});
document.addEventListener('livewire:init', () => {

    Livewire.on('actualizarProps', data => {
        console.log('Evento actualizarProps recibido:', data); // <-- debug general

        for (const prop in data) {
            const value = data[prop];
            console.log(`Procesando propiedad: ${prop}, valor:`, value); // <-- debug propiedad por propiedad

            // 1) Checkbox / switch (boolean)
            const checkbox = document.querySelector(`input[type="checkbox"][wire\\:model="${prop}"]`);
            if (checkbox && typeof value === 'boolean') {
                console.log(`Actualizando checkbox ${prop} a ${value}`);
                checkbox.checked = value;
            }

            // 2) Input de texto o número
            const input = document.querySelector(`input[wire\\:model="${prop}"]`);
            if (input && (typeof value === 'string' || typeof value === 'number')) {
                console.log(`Actualizando input ${prop} a ${value}`);
                input.value = value;
            }

            // 3) Span / elemento de texto
            const span = document.querySelector(`[data-prop="${prop}"]`);
            if (span) {
                console.log(`Actualizando span ${prop} a ${value}`);
                span.textContent = value;
            }

            // 4) Toggle visual mediante clases
            const toggle = document.querySelector(`[data-toggle="${prop}"]`);
            if (toggle && typeof value === 'boolean') {
                console.log(`Actualizando toggle ${prop} a ${value}`);
                toggle.classList.toggle('activo', value);
            }
        }
    });
});

// DRAG AND DROP
function dragModal(modal) {
    if (!modal) return;
    const header = modal.querySelector('.cardPrin-header');
    if (!header) return;
    let isDragging = false;
    let offsetX = 0, offsetY = 0;
    modal.style.position = 'fixed';
    modal.style.zIndex = 10000;
    header.style.cursor = 'move';
    header.addEventListener('mousedown', function (e) {
        isDragging = true;
        const rect = modal.getBoundingClientRect();
        offsetX = e.clientX - rect.left;
        offsetY = e.clientY - rect.top;
        document.body.style.userSelect = 'none';
        e.preventDefault();
    });
    document.addEventListener('mousemove', function (e) {
        if (!isDragging) return;
        modal.style.left = (e.clientX - offsetX) + 'px';
        modal.style.top = (e.clientY - offsetY) + 'px';
    });
    document.addEventListener('mouseup', function () {
        isDragging = false;
        document.body.style.userSelect = '';
    });
}

function handleDragStart(event) {
    const id = event.target.dataset.id;
    const type = event.target.dataset.type;
    if (!id || !type) return;
    document.querySelectorAll('.soltar').forEach(zone => {
        zone.classList.remove('activa');
        if (zone.dataset.accepts === type) {
            zone.classList.add('activa'); // Muestra solo la dropzone correspondiente
        }
    });
    event.dataTransfer.setData('text/plain', JSON.stringify({ id, type }));
    console.log('[DragStart] ID:', id, 'Tipo:', type);
}

function handleDragOver(event) {
    event.preventDefault(); // Necesario para permitir drop
}


function handleDrop(event) {
    event.preventDefault();
    // Oculta todas las zonas activas
    document.querySelectorAll('.soltar').forEach(zone => {
        zone.classList.remove('activa');
    });
    const dataTransfer = event.dataTransfer.getData('text/plain');
    const { id, type } = JSON.parse(dataTransfer || '{}');
    const dropzone = event.target.closest('.soltar');
    const vista = dropzone?.dataset.vista;
    if (!id || !type || !vista) return;
    const acciones = {
        modelo: {
            presuelems: 'nuevoModelo',
            modelopremats: 'cargarModelo'
        },
        material: {
            modelopremats: 'nuevoMaterial',
            materials: 'cargarMaterial',
            reglas: 'cargarMaterialRelacion'
        }
    };

    const evento = acciones[type]?.[vista];

    if (evento) {
        Livewire.dispatch(evento, { id });
    }
}

window.addEventListener('dragend', () => {
    document.querySelectorAll('.soltar').forEach(zone => {
        zone.classList.remove('activa'); // Oculta dropzones si se cancela el drag
    });
});

//Enviar logs: $this->dispatch('log', ['Hola',$id]); -> ['Hola', 1]
window.addEventListener('log', event => {
    console.log('🔍 Livewire log:', event.detail);
});

// Expandir Imagen 
document.addEventListener('click', function (e) {
    const img = e.target.closest('.ImgExpandible');
    if (img) {
        const modal = document.getElementById('ImgModal');
        const modalImg = document.getElementById('ImgModalImg');
        if (!modal || !modalImg) return;

        modalImg.src = img.dataset.src || img.getAttribute('src');
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
        return;
    }
    if (e.target && e.target.id === 'ImgModal') {
        cerrarModal();
    }
});
function cerrarModal() {
    const modal = document.getElementById('ImgModal');
    const modalImg = document.getElementById('ImgModalImg');

    if (modal) modal.style.display = 'none';
    if (modalImg) modalImg.src = '';
    document.body.style.overflow = '';
}