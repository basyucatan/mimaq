function dragModal(modal) {
    if (!modal) return;
    const encabezado = modal.querySelector('.cardPrin-header');
    if (!encabezado) return;
    let estaArrastrando = false;
    let desplazamientoX = 0;
    let desplazamientoY = 0;
    encabezado.addEventListener('mousedown', function (e) {
        estaArrastrando = true;
        const rectangulo = modal.getBoundingClientRect();
        desplazamientoX = e.clientX - rectangulo.left;
        desplazamientoY = e.clientY - rectangulo.top;
        modal.style.position = 'fixed';
        modal.style.left = rectangulo.left + 'px';
        modal.style.top = rectangulo.top + 'px';
        modal.style.margin = '0';
        document.body.style.userSelect = 'none';
    });
    document.addEventListener('mousemove', function (e) {
        if (!estaArrastrando) return;
        modal.style.left = (e.clientX - desplazamientoX) + 'px';
        modal.style.top = (e.clientY - desplazamientoY) + 'px';
    });
    document.addEventListener('mouseup', function () {
        estaArrastrando = false;
        document.body.style.userSelect = '';
    });
}