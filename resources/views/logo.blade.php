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
                <stop offset="0%" stop-color="#dd2c00" />
                <stop offset="20%" stop-color="#ff6d00" />
                <stop offset="65%" stop-color="#ffea00" />
                <stop offset="88%" stop-color="#0066FF" />
                <stop offset="95%" stop-color="#ffffff" />
                <stop offset="100%" stop-color="#ffffff" />
            </linearGradient>
            <linearGradient id="gradCola" x1="0%" y1="100%" x2="0%" y2="0%">
                <stop offset="0%" stop-color="#ffffff" />
                <stop offset="5%" stop-color="#ffffff" />
                <stop offset="45%" stop-color="#ffff00" />
                <stop offset="55%" stop-color="#ff9900" />
                <stop offset="70%" stop-color="#bf0000" />
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
        <g transform="translate(100, 250) scale(0.13, -0.13)" fill="url(#gradFuego)" stroke="none">
            <path d="M 1113.4 1375.22 c -24.32 -32.68 -41.42 -68.02 -44.46 -91.96 c -4.56 -40.28 17.48 -121.22 47.5 -174.8 c 31.54 -56.24 95.76 -127.3 152.76 -169.48 c 30.78 -22.8 38.38 -22.8 22.8 -0.38 c -18.24 26.6 -37.62 69.92 -42.18 95 c -9.12 49.4 4.18 98.04 35.72 128.06 c 15.96 15.58 48.64 37.24 64.98 43.32 c 7.22 2.66 7.22 2.66 -0.38 -8.36 c -21.28 -30.4 -21.66 -83.98 -1.14 -147.82 c 24.32 -75.62 45.22 -107.54 117.42 -182.4 c 55.48 -57 157.32 -171.76 182.02 -204.44 c 40.28 -52.82 55.1 -94.62 54.72 -150.86 c -0.38 -39.52 -12.54 -96.52 -26.98 -124.64 c -4.56 -8.74 -7.98 -17.48 -7.98 -20.14 c 0 -2.28 11.4 -18.24 25.46 -35.34 c 30.78 -38 50.54 -77.52 53.2 -107.92 c 3.04 -32.68 -5.7 -58.14 -33.44 -97.28 c -23.56 -33.06 -60.8 -74.48 -66.88 -74.48 c -1.52 0 -2.28 12.16 -1.9 26.98 c 2.28 47.88 -21.66 102.98 -62.32 142.5 c -32.68 31.54 -54.34 40.66 -198.74 85.5 c -47.12 14.44 -98.42 32.3 -113.62 39.9 c -81.32 39.52 -128.44 138.7 -96.14 201.78 c 9.5 19 36.48 43.7 61.18 56.24 c 11.02 5.7 20.52 9.5 21.28 8.74 c 0.76 -0.76 -2.28 -11.02 -6.84 -22.42 c -12.16 -30.78 -11.4 -71.06 1.52 -99.18 c 18.24 -39.52 51.3 -67.64 92.72 -78.66 c 8.74 -2.28 49.78 -9.5 91.2 -15.58 c 90.44 -14.06 118.56 -20.14 145.16 -32.3 l 19.76 -9.12 l 0 27.36 c 0 54.34 -20.14 144.02 -41.04 183.16 c -5.7 10.26 -23.18 32.3 -38.76 49.02 c -77.9 81.7 -189.24 149.34 -267.14 161.88 c -42.56 6.84 -132.62 -6.46 -224.2 -32.68 l -34.96 -10.26 l -26.6 14.06 c -65.36 34.58 -116.66 95.38 -136.8 161.88 c -8.36 28.5 -11.4 83.6 -6.08 117.8 c 5.32 33.44 15.58 73.34 18.62 70.3 c 1.14 -1.14 6.08 -14.06 10.64 -28.5 c 12.92 -41.04 36.48 -77.52 73.34 -114 c 35.34 -34.58 66.12 -54.72 118.18 -77.14 c 36.86 -15.58 59.28 -21.28 83.22 -21.66 l 16.72 0 l -17.86 14.06 c -37.24 28.88 -99.94 113.24 -125.4 169.1 c -24.32 52.06 -33.44 90.44 -33.82 139.84 c -0.38 37.62 1.14 47.5 9.5 70.68 c 5.32 14.82 15.96 36.48 23.56 48.26 c 21.28 31.92 69.92 76 101.84 91.96 c 14.82 7.22 27.74 12.54 28.5 11.78 c 0.76 -0.76 -7.22 -12.92 -17.86 -27.36 z M 1241.84 729.6 c 30.4 -7.22 58.52 -21.28 102.98 -50.92 c 30.4 -20.52 41.04 -30.78 31.54 -30.78 c -1.52 0 -23.94 4.56 -50.16 9.88 c -36.86 7.6 -53.2 9.12 -72.58 7.6 c -85.5 -8.36 -150.48 -59.66 -197.6 -156.18 c -25.08 -52.06 -33.82 -89.68 -36.48 -158.46 c -5.7 -145.54 34.2 -284.62 152.76 -534.66 c 68.02 -144.02 95 -224.58 114.38 -344.28 c 9.5 -57.38 9.5 -178.98 0 -229.9 c -22.8 -124.26 -69.16 -212.04 -157.7 -300.2 c -68.02 -68.02 -133.76 -109.44 -215.84 -137.18 c -123.12 -41.42 -288.8 -69.54 -335.16 -56.62 c -27.36 7.22 -59.66 42.56 -83.6 91.2 c -10.64 22.04 -19.38 41.04 -19.38 42.18 c 0 1.14 12.54 -0.38 27.74 -3.42 c 38.38 -7.6 121.98 -7.6 162.64 0.38 c 147.82 28.88 273.22 128.06 345.42 272.08 c 49.4 99.18 72.96 237.12 55.48 327.94 c -14.44 76 -58.14 213.94 -122.36 386.08 c -53.58 144.02 -69.92 194.94 -83.22 260.68 c -25.46 128.06 -22.04 225.72 11.78 323.76 c 25.08 73.72 72.58 153.9 115.52 195.32 c 71.82 69.54 174.8 104.12 253.84 85.5 z M 1465.28 592.42 c 22.8 -20.14 45.6 -41.04 50.16 -46.36 c 20.52 -23.18 29.64 -59.28 18.62 -74.1 c -12.92 -18.24 -57.76 -10.26 -85.88 14.82 c -20.9 19 -30.02 37.62 -33.44 69.92 c -2.28 24.7 1.52 72.2 6.46 72.2 c 1.52 0 21.66 -16.34 44.08 -36.48 z" />
        </g>
    </svg>
</div>