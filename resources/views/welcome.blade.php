@extends('layouts.app')
@section('title', __('Welcome'))
@section('content')
<style>
.txtFuego {
    font-size: 3.5rem;
    font-weight: 800;
    letter-spacing: 2px;
    margin-top: -10px;
    text-transform: uppercase;
    color: #fff;
    text-shadow: 0 0 10px #fff, 0 0 20px #ffcc00, 0 0 30px #ff4d00, 0 0 40px #ff4d00;
    position: relative;
    z-index: 2;
}

@keyframes flotar3D {
    0%, 100% { transform: rotateY(-18deg) rotateX(15deg) translateY(0px); }
    50% { transform: rotateY(18deg) rotateX(-10deg) translateY(-25px); }
}
@keyframes pulsoFuego {
    0%, 100% { transform: scale(1); opacity: 0.8; }
    50% { transform: scale(1.05) translateY(-5px); opacity: 1; }
}
@keyframes chispaAscendente {
    0% { transform: translateY(0) scale(1); opacity: 1; }
    100% { transform: translateY(-150px) scale(0); opacity: 0; }
}
</style>

<div class="container py-1">
    <div class="text-center shadow-lg" 
        style="padding: 5px; background: linear-gradient(45deg, #000, #022b8a); border-radius: 20px;">
        <div style="height: 350px;">
            <svg style="height: auto; width: 320px; transform-style: preserve-3d; animation: flotar3D 8s ease-in-out infinite; filter: drop-shadow(0 0 20px rgba(255,100,0,0.6));" 
                 viewBox="0 0 400 450" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <linearGradient id="gradMain" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" stop-color="#fffb00"/> 
                        <stop offset="40%" stop-color="#ff9900"/> 
                        <stop offset="100%" stop-color="#e61919"/> 
                    </linearGradient>

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
                <g fill="#fffb00">
                    <circle cx="150" cy="380" r="3" style="animation: chispaAscendente 2s infinite 0.1s;" />
                    <circle cx="250" cy="390" r="2.5" style="animation: chispaAscendente 1.8s infinite 0.5s;" />
                    <circle cx="200" cy="370" r="4" fill="#fff" style="animation: chispaAscendente 2.5s infinite 0.9s;" />
                </g>
                <g style="transform: translate(70px, 40px);">
                    <path d="M140 40 C90 90, 80 160, 110 230 C130 270, 120 310, 85 350 C170 330, 230 260, 210 190 C195 140, 210 100, 260 60 C200 80, 170 70, 140 40 Z" 
                          fill="url(#gradMain)" />

                    <path d="M155 90 C130 120, 125 170, 150 210 C165 235, 160 265, 140 295 C190 270, 210 230, 200 190 C190 150, 200 120, 225 95 C190 110, 170 105, 155 90 Z" 
                          fill="url(#luzInterior)" 
                          style="animation: brilloIntenso 3s ease-in-out infinite;" />
                </g>
            </svg>
        </div>

        <div class="txtFuego">WMM</div>
        <p class="text-white-50 lead">Soluciones Integrales</p>
        
        @auth
            <div class="mt-4">
                <a href="#" class="btn btn-light btn-lg px-5">Primera página</a>
            </div>
        @endauth
    </div>
    
    <div class="text-center mt-4 text-muted small">
        WMM © {{ date('Y') }} | Sistemas Informáticos
    </div>
</div>
@endsection