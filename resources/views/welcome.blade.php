@extends('layouts.app')
@section('title', __('Welcome'))
@section('content')
<style>
.hero-emerita {
    /* Fondo negro profundo que se degrada a un azul muy oscuro */
    background: linear-gradient(180deg, #000000 0%, #010a1f 100%);
    color: white;
    padding: 60px 20px;
    border-radius: 12px;
    margin-bottom: 30px;
    position: relative;
    overflow: hidden;
    /* Sutil resplandor interno */
    box-shadow: inset 0 0 50px rgba(30, 58, 138, 0.2);
}
.hero-emerita h1 {
    font-size: 3.5rem;
    font-weight: 800;
    letter-spacing: 2px;
    margin-top: 10px;
    text-transform: uppercase;
    /* Efecto de texto ardiente */
    color: #fff;
    text-shadow: 0 0 10px #fff, 0 0 20px #ffcc00, 0 0 30px #ff4d00;
}
.hero-emerita p {
    font-size: 1.3rem;
    opacity: .9;
    font-weight: 300;
}
.hero-btn {
    padding: 12px 24px;
    border-radius: 8px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    transition: all 0.3s ease;
}
.hero-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(255, 255, 255, 0.3);
}

/* --- Contenedor SVG y Animaciones --- */
.fenix-container {
    height: 220px;
    display: flex;
    justify-content: center;
    align-items: center;
    filter: drop-shadow(0 0 15px #ff4d00);
}

/* Animación de flotación suave general */
@keyframes flotarAve {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
}

/* Animación de pulso del fuego base (Intensidad) */
@keyframes pulsoFuego {
    0%, 100% { transform: scale(1) translateY(0); opacity: 0.8; }
    50% { transform: scale(1.1) translateY(-5px); opacity: 1; }
}

/* Animación de aleteo simbólico y majestuoso */
@keyframes aleteoMajestuoso {
    0%, 100% { transform: scaleX(1); }
    50% { transform: scaleX(0.95); }
}

/* Partículas ascendentes */
@keyframes particulaAscendente {
    0% { transform: translateY(0) scale(1); opacity: 1; }
    100% { transform: translateY(-120px) scale(0); opacity: 0; }
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
            <svg viewBox="0 0 200 200" width="220" height="220" style="animation: flotarAve 4s ease-in-out infinite;">
                <defs>
                    <radialGradient id="gradFuegoIntenso" cx="50%" cy="50%" r="50%">
                        <stop offset="0%" stop-color="#fffb00" /> <stop offset="30%" stop-color="#ff9900" /> <stop offset="70%" stop-color="#ff4d00" /> <stop offset="100%" stop-color="#ff4d00" stop-opacity="0" />
                    </radialGradient>
                    <linearGradient id="gradAveOro" x1="0%" y1="0%" x2="0%" y2="100%">
                        <stop offset="0%" stop-color="#ffffff" /> <stop offset="40%" stop-color="#fffb00" /> <stop offset="100%" stop-color="#ff9900" /> </linearGradient>
                </defs>

                <ellipse cx="100" cy="180" rx="70" ry="25" fill="url(#gradFuegoIntenso)" style="animation: pulsoFuego 2s ease-in-out infinite;" />
                
                <circle cx="90" cy="170" r="3" fill="#fffb00" style="animation: particulaAscendente 2s infinite 0.2s;" />
                <circle cx="110" cy="175" r="2.5" fill="#fff" style="animation: particulaAscendente 1.5s infinite 0.7s;" />
                <circle cx="100" cy="165" r="3" fill="#ff4d00" style="animation: particulaAscendente 2.2s infinite 1s;" />
                <circle cx="85" cy="160" r="2" fill="#ff9900" style="animation: particulaAscendente 1.8s infinite 1.3s;" />

                <g id="fenixSimbofico" fill="url(#gradAveOro)" style="transform-origin: center bottom; animation: aleteoMajestuoso 3s ease-in-out infinite;">
                    
                    <path d="M100,160 
                             Q70,140 40,110 
                             Q10,70 30,40
                             Q50,50 70,80
                             Q90,110 100,130 Z" /> <path d="M100,160 
                             Q130,140 160,110 
                             Q190,70 170,40
                             Q150,50 130,80
                             Q110,110 100,130 Z" /> <path d="M100,165 
                             Q90,130 100,90
                             Q110,130 100,165 Z" />

                    <circle cx="100" cy="82" r="9" />
                    <path d="M100,75 
                             Q105,60 115,65
                             Q105,70 100,75 Z" /> <path d="M98,76 
                             Q90,65 90,70
                             Q95,73 98,76 Z" /> <ellipse cx="100" cy="155" rx="15" ry="30" fill="url(#gradAveOro)" opacity="0.5" />
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