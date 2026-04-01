@extends('layouts.app')
@section('title', __('Welcome'))
@section('content')
<style>
.hero-emerita {
    /* Fondo negro profundo que se degrada a un azul muy oscuro */
    background: linear-gradient(45deg, #000000 0%, #022b8a 100%);
    color: white;
    padding: 60px 20px;
    border-radius: 12px;
    margin-bottom: 30px;
    
    position: relative;
    overflow: hidden;
    /* Resplandor interno sutil */
    box-shadow: inset 0 0 60px rgba(30, 58, 138, 0.3);
}

.hero-emerita h1 {
    font-size: 3.5rem;
    font-weight: 800;
    letter-spacing: 2px;
    margin-top: -10px; /* Ajuste para acercar al fénix */
    text-transform: uppercase;
    color: #fff;
    /* Efecto de texto ardiente reforzado */
    text-shadow: 0 0 10px #fff, 0 0 20px #ffcc00, 0 0 30px #ff4d00, 0 0 40px #ff4d00;
    position: relative;
    z-index: 2;
}

.hero-emerita p {
    font-size: 1.3rem;
    opacity: .9;
    font-weight: 300;
    position: relative;
    z-index: 2;
}

/* --- Contenedor con Perspectiva 3D e Intensidad --- */
.fenix-container {
    height: 320px; /* Un poco más alto para el fuego base */
    display: flex;
    justify-content: center;
    align-items: center;
    perspective: 1200px; /* Profundidad de la escena */
    position: relative;
}

.fenix-svg-3d {
    width: 300px;
    height: auto;
    transform-style: preserve-3d;
    /* Resplandor general del ave (Glow) */
    filter: drop-shadow(0 0 25px rgba(255, 100, 0, 0.8));
    animation: flotar3D 6s ease-in-out infinite;
    position: relative;
    z-index: 1;
}

/* Animación que rota el ave en los ejes X e Y para el efecto 3D */
@keyframes flotar3D {
    0%, 100% { 
        transform: rotateY(-18deg) rotateX(15deg) translateY(0px); 
    }
    50% { 
        transform: rotateY(18deg) rotateX(-10deg) translateY(-25px); 
    }
}

/* Brillo del detalle interno */
@keyframes brilloIntenso {
    0%, 100% { opacity: 0.8; filter: blur(1px); }
    50% { opacity: 1; filter: blur(0px); }
}

/* Pulso del Fuego Base */
@keyframes pulsoFuego {
    0%, 100% { transform: scale(1); opacity: 0.8; }
    50% { transform: scale(1.05) translateY(-5px); opacity: 1; }
}

/* Animación de Chispas Ascendentes */
@keyframes chispaAscendente {
    0% { transform: translateY(0) scale(1); opacity: 1; }
    100% { transform: translateY(-150px) scale(0); opacity: 0; }
}

.hero-btn {
    position: relative;
    z-index: 2;
}

.footer-emerita {
    margin-top: 40px;
    text-align: center;
    font-size: .85rem;
    color: #777;
}
</style>

<div class="container py-4">
    <div class="hero-emerita text-center">
        <div class="fenix-container">
            
            <svg class="fenix-svg-3d" viewBox="0 0 400 450" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <linearGradient id="gradMain" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" stop-color="#fffb00"/> <stop offset="40%" stop-color="#ff9900"/> <stop offset="100%" stop-color="#e61919"/> </linearGradient>

                    <radialGradient id="luzInterior" cx="50%" cy="50%" r="50%">
                        <stop offset="0%" stop-color="#ffffff" stop-opacity="0.9"/>
                        <stop offset="100%" stop-color="#fffb00" stop-opacity="0"/>
                    </radialGradient>

                    <radialGradient id="gradFuegoBase" cx="50%" cy="50%" r="50%">
                        <stop offset="0%" stop-color="#ffcc00" stop-opacity="1"/>
                        <stop offset="50%" stop-color="#ff4d00" stop-opacity="0.8"/>
                        <stop offset="100%" stop-color="#ff4d00" stop-opacity="0"/>
                    </radialGradient>
                </defs>

                <ellipse cx="200" cy="410" rx="100" ry="30" fill="url(#gradFuegoBase)" style="animation: pulsoFuego 2s ease-in-out infinite;" />
                <ellipse cx="200" cy="400" rx="60" ry="40" fill="url(#gradFuegoBase)" opacity="0.6" style="animation: pulsoFuego 2s ease-in-out infinite 0.5s;" />

                <g fill="#fffb00">
                    <circle cx="150" cy="380" r="3" style="animation: chispaAscendente 2s infinite 0.1s;" />
                    <circle cx="250" cy="390" r="2.5" style="animation: chispaAscendente 1.8s infinite 0.5s;" />
                    <circle cx="200" cy="370" r="4" fill="#fff" style="animation: chispaAscendente 2.5s infinite 0.9s;" />
                    <circle cx="180" cy="395" r="2" style="animation: chispaAscendente 1.5s infinite 1.2s;" />
                    <circle cx="220" cy="385" r="3" fill="#ff4d00" style="animation: chispaAscendente 2.2s infinite 1.5s;" />
                    <circle cx="160" cy="360" r="2.5" style="animation: chispaAscendente 2.8s infinite 1.8s;" />
                    <circle cx="240" cy="365" r="2" fill="#fff" style="animation: chispaAscendente 1.9s infinite 2.1s;" />
                </g>

                <g style="transform: translate(70px, 40px);">
                    <path d="M140 40
                             C90 90, 80 160, 110 230
                             C130 270, 120 310, 85 350
                             C170 330, 230 260, 210 190
                             C195 140, 210 100, 260 60
                             C200 80, 170 70, 140 40 Z"
                          fill="url(#gradMain)"
                    />

                    <path d="M155 90
                             C130 120, 125 170, 150 210
                             C165 235, 160 265, 140 295
                             C190 270, 210 230, 200 190
                             C190 150, 200 120, 225 95
                             C190 110, 170 105, 155 90 Z"
                          fill="url(#luzInterior)"
                          style="animation: brilloIntenso 3s ease-in-out infinite;"
                    />
                </g>
            </svg>
        </div>

        <h1>Fénix</h1>
        <p>Soluciones Integrales</p>
        
        @auth
            <div class="mt-4">
                <a href="#" class="btn btn-light hero-btn">Primera página</a>
            </div>
        @endauth
    </div>
    
    <div class="footer-emerita">
        Fénix © {{ date('Y') }} | Sistemas Informáticos
    </div>
</div>
@endsection