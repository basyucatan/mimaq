@extends('layouts.app')

@section('title', 'Log in')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-md-6">
            <div class="cardPrin">
                <div class="cardPrin-header">Acceso</div>
                <div class="cardPrin-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="mb-2">
                            <label for="telefono" class="etiBase">Teléfono</label>
                            <input id="telefono" type="tel" name="telefono" class="inpBase" value="{{ old('telefono') }}" required autofocus>
                            @error('telefono')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="password" class="etiBase">Contraseña</label>
                            <div class="position-relative">
                                <input id="password" type="password" name="password" class="inpBase pe-5" required>
                                <button type="button" class="bot botAzul position-absolute top-0 end-0 h-100 px-2" onclick="togglePassword()">👁</button>
                            </div>
                            @error('password')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="cardPrin-footer" style="border-radius: 0 0 10px 10px;">
                            <button type="submit" class="bot botAzul">Iniciar Sesión</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection