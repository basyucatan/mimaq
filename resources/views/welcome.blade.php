@extends('layouts.app')
@section('title', __('Welcome'))

@section('content')

<style>

.hero-emerita{
    background: linear-gradient(135deg,#0f172a,#1e3a8a);
    color:white;
    padding:70px 20px;
    border-radius:12px;
    margin-bottom:30px;
}

.hero-emerita h1{
    font-size:3rem;
    font-weight:700;
    letter-spacing:1px;
}

.hero-emerita p{
    font-size:1.2rem;
    opacity:.9;
}

.hero-btn{
    padding:10px 20px;
    border-radius:8px;
    font-weight:600;
    margin-right:10px;
}


.footer-emerita{
    margin-top:40px;
    text-align:center;
    font-size:.85rem;
    color:#777;
}

</style>


<div class="container py-4">

    <div class="hero-emerita text-center">

        <h1>Emerita</h1>

        <p>
            Sistema de integral de gestión para proyectos
            de fabricación y presupuestos.
        </p>
        @auth
            <div class="mt-4">
                <a href="/ocompras" class="btn btn-light hero-btn">Órdenes de Compra</a>
            </div>
        @endauth
    </div>
    <div class="footer-emerita">
        Emerita © {{ date('Y') }} — Sistema de gestión
    </div>

</div>

@endsection
