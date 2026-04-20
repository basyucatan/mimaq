<style>
    .contenedorLogo {
        height: 350px;
        display: flex;
        justify-content: center;
        align-items: center;
        overflow: hidden;
    }
    .svgFenix {
        height: 100%;
        width: auto;
        transform-style: preserve-3d;
        animation: flotarTresD 8s ease-in-out infinite;
        filter: drop-shadow(0 0 20px rgba(255, 100, 0, 0.6));
    }
    @keyframes flotarTresD {
        0%, 100% { transform: rotateY(-18deg) rotateX(15deg) translateY(0px); }
        50% { transform: rotateY(18deg) rotateX(-10deg) translateY(-25px); }
    }
    @keyframes pulsoFuego {
        0%, 100% { transform: scale(1); opacity: 0.8; }
        50% { transform: scale(1.05) translateY(-5px); opacity: 1; }
    }
    @keyframes chispaCaotica {
        0% { transform: translate(0, 0) scale(1); opacity: 1; }
        25% { transform: translate(calc(var(--dx1) * 0.2), -80px) scale(1.2); opacity: 1; }
        50% { transform: translate(var(--dx2), var(--dy2)) scale(1); opacity: 1; }
        75% { transform: translate(var(--dx3), var(--dy3)) scale(0.8); opacity: 0.6; }
        100% { transform: translate(var(--dx4), var(--dy4)) scale(0); opacity: 0; }
    }
</style>
<div class="contenedorLogo">
    <svg class="svgFenix" viewBox="0 0 400 450" xmlns="http://www.w3.org/2000/svg">
        <defs>
            <linearGradient id="gradFuego" x1="0%" y1="100%" x2="0%" y2="0%">
                <stop offset="20%" stop-color="#bf0000" />
                <stop offset="50%" stop-color="#ff9900" />
                <stop offset="80%" stop-color="#ffff00" />
                {{-- <stop offset="100%" stop-color="#00ffff" /> --}}
            </linearGradient>
            <linearGradient id="gradCola" x1="0%" y1="100%" x2="0%" y2="0%">
                <stop offset="30%" stop-color="#bf0000" />
                <stop offset="45%" stop-color="#ff9900" />
                <stop offset="55%" stop-color="#ffff00" />
                {{-- <stop offset="75%" stop-color="#00ffff" /> --}}
                <stop offset="95%" stop-color="#ffffff" />
                <stop offset="100%" stop-color="#ffffff" />
            </linearGradient>
            <radialGradient id="gradFuegoBase" cx="50%" cy="50%" r="50%">
                <stop offset="0%" stop-color="#ffcc00" stop-opacity="1" />
                <stop offset="100%" stop-color="#ff4d00" stop-opacity="0" />
            </radialGradient>
        </defs>
        <ellipse cx="225" cy="400" rx="100" ry="25" fill="url(#gradFuegoBase)" style="animation: pulsoFuego 2s ease-in-out infinite;" />
        <g>
            @for ($i = 0; $i < 50; $i++)
                @php
                    $radioChispa = rand(5, 35) / 10;
                    $retrasoAnimacion = rand(0, 40) / 10;
                    $duracionAnimacion = rand(50, 100) / 15;
                    $posicionX = rand(210, 270);
                    $posicionY = rand(390, 410);
                    if ($i < 25) {
                        $factorColor = $i / 24;
                        $rojoComponente = 255;
                        $verdeComponente = (int) (255 + $factorColor * (251 - 255));
                        $azulComponente = (int) (255 + $factorColor * (0 - 255));
                    } else {
                        $factorColor = ($i - 25) / 24;
                        $rojoComponente = (int) (255 + $factorColor * (191 - 255));
                        $verdeComponente = (int) (251 + $factorColor * (0 - 251));
                        $azulComponente = 0;
                    }
                    $colorHexadecimal = sprintf('#%02x%02x%02x', $rojoComponente, $verdeComponente, $azulComponente);
                @endphp
                <circle cx="{{ $posicionX }}" cy="{{ $posicionY }}" r="{{ $radioChispa }}" fill="{{ $colorHexadecimal }}" style="animation: chispaCaotica {{ $duracionAnimacion }}s infinite {{ $retrasoAnimacion }}s; --dx1: {{ rand(-40, 40) }}px; --dx2: {{ rand(-80, 80) }}px; --dx3: {{ rand(-120, 120) }}px; --dx4: {{ rand(-160, 160) }}px; --dy2: {{ rand(-130, -170) }}px; --dy3: {{ rand(-220, -280) }}px; --dy4: {{ rand(-350, -420) }}px;" />
            @endfor
        </g>
        <g transform="translate(320, 420) rotate(180) scale(0.85)">
            <path d="M91 337.8c-12-6.1-22.7-14.1-33.7-25.3-7.3-7.4-12.9-14.3-18.6-22.8-14.1-21-22.4-44.1-24.6-68.4-.4-4.6-.5-17.1-.1-21.4 1.8-20.1 6.3-37 15.4-57.5 2.8-6.3 7.5-16.4 8.5-18.3 4.4-8.2 13.1-21.2 23.9-35.7 7.9-10.6 15.9-20.5 16.3-20.1 0 0-.3 1.5-.8 3.1-5.8 19.1-7.3 37.2-4.2 49.4 1.4 5.5 5.8 17 8.9 23 2.5 4.8 4.5 7.9 13.5 21.2 6.6 9.6 9.1 13.4 10.1 15.2 4.5 7.5 7.7 18.6 7.7 26.8 0 2.7 0 3.1-.4 3.9-.2.5-.4.9-.4.9s1.3.1 2.7.3c3.7.3 6 .7 7.8 1.3 1.5.5 3 1.4 3.1 1.8.1.3-.8.7-3.2 1.4-1.2.3-3 .1-3.9 1.5-1.5.7-2 1.1-4.3 3.5-4 4.1-6.7 5.7-12.1 7.5-8.4 2.8-14 3.1-20 1.1-2.1-.7-8.3-3.5-8.2-3.8.1-.1 1.4-.7 3-1.3 4.9-1.9 7.4-3.4 8.8-5.3 1.1-1.4 1.4-2.5 1.6-6.2.5-7.1-.7-11.9-4.6-17.6-4.2-6.2-7.9-8.5-13.8-8.5-2.8 0-4.6.5-7.8 2-5.8 2.8-8.5 6.5-9.6 12.8-.5 2.9-.3 7.9.4 11.6 2.1 11.1 8.4 24.4 17.3 36.4 6.4 8.6 14.6 18.2 22.1 25.5 7.8 7.7 14.6 13.2 23.8 19.4 19.2 12.8 38.2 20.1 68.9 26.2 3.3.7 6 .1 6.1.1.1 0 .1.1 0 .2-.3.3-5.8 1.7-9.4 2.4-3.9.8-9.4 1.5-13.6 1.8-4.4.3-14.8.1-18.7-.3-12.8-1.4-24.5-4.7-35.7-9.9-22.1-10.3-48.9-34-63.8-56.5-2.1-3.1-2.2-3.1-1.9-.1.5 4.9 2.1 11.4 4.2 16.6.9 2.4 3.5 7.5 5.2 10.5 6.8 11.8 15.9 24.6 31.2 43.7 6.4 8 6.8 8.5 6.6 8.5-.1 0-1.8-.9-4-2z" fill="url(#gradFuego)" />
            <path d="M233.1 301.3c0-6.2-.3-9.2-1.4-14.7-3.4-17-12.4-31.5-26.4-42.7-3.2-2.6-4.5-3.4-16.5-11.3-21.9-14.3-43.7-28.6-50.3-33.2-15.7-10.9-26.5-21.2-33.8-32.1-7.1-10.6-10.8-21.1-13.9-38.8-.5-2.8-3.1-19.5-3.5-22.8-.1-.7.1-.2.9 1.7 8.2 21.7 22.4 41.1 42 57.6 3.4 2.8 9.7 7.4 15.1 11.1 5.6 3.8 6.8 4.5 18.3 11.8 26 16.4 37.1 24.6 49.2 36.6 8.5 8.4 12.5 13.5 16.1 20.4 4.7 9.1 7 18.7 7.5 30.3.4 8.9-.5 18.8-2.7 29l-.5 2.5v-5.4z" fill="url(#gradFuego)" />
            <path d="M246.9 267.1l-.8-3.7c-1.3-6.4-2.8-11.7-4.7-16.8-7.6-21-20.9-38.1-38.9-50.2-1.3-.9-5.3-3.3-8.8-5.4-30.6-18.5-49.9-32.6-64.8-47.5-8.9-8.9-14.3-16-19.9-26-6.6-11.8-12.3-27.8-13.4-37.8-.5-4.5-.2-11.5.8-16.9 2.7-14.9 11.7-33.6 24.2-50.3 4-5.4 9.3-11.5 10.4-12.1.4-.2.4-.2.3.7-.4 2.6-1.5 7.6-3.4 15.3-.6 2.6-3.3 10.1-4.2 13.8-2.5 10.1-3.3 13.8-4.2 19.3-4.9 28.6.1 52.7 15.6 76.3 10.8 16.4 30.1 35.2 63.2 61.7 13.7 10.9 15 12 16.8 13.6 7.4 6.7 14.3 16.9 19.1 28.4 5.5 13.1 8.9 28.5 9.3 42.1.1 5-.3 10.2-.8 9.4z" fill="url(#gradCola)" />
        </g>
    </svg>
</div>