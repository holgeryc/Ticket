@extends('layouts.app')
@section('title', __('Iniciar Sesión'))
@section('content')
    <div class="container-fluid vh-100" style="background-color:#ffffff">
        <img class="transparent" src="https://www.gereducusco.gob.pe/images/varios/logo2022_original.png"
            hret="https://www.gereducusco.gob.pe/" height="280" width="230"
            style="position:relative; top:100px; left:50px">

        <div class="row justify-content-end ">

            <div class="col-md-8">

                <div class="card login-card" style="position:relative; top:-170px; left:-100px">
                    
                    <div class="card-header">{{ __('Login') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="row mb-3">
                                <label for="email"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Correo Electronico') }}</label>

                                <div class="col-md-6">
                                    <input id="email" type="email"
                                        class="form-control @error('email') is-invalid @enderror" name="email"
                                        value="{{ old('email') }}" required autocomplete="email" autofocus>

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Contraseña') }}</label>

                                <div class="col-md-6">
                                    <input id="password" type="password"
                                        class="form-control @error('password') is-invalid @enderror" name="password"
                                        required autocomplete="current-password">

                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6 offset-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                            {{ old('remember') ? 'checked' : '' }}>

                                        <label class="form-check-label" for="remember">
                                            {{ __('recordar datos') }}
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-danger">
                                        {{ __('Iniciar sesion') }}
                                    </button>


                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div style="font-size: 50px; text-align: center; top: 50%; left: 50%; transform: translate(-25%, -200%);font-family: Georgia, sans-serif;color: rgb(113, 190, 209);">
                    SATU <span style="font-size: 40px;">v(0.1)</span>
                </div>
            </div>
        </div>
    </div>
    <div style="background-color: rgba(90, 90, 94, 0.644);position:fixed; bottom: 0; width: 100%; height:45px; color: white;">
        <footer class="mt-1">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6">
                    </div>
                    <div class="col-md-6 text-md-end">
                        <p style="margin-bottom: 5px; line-height: 1;">
                            Copyright © 2023 Gerencia Regional de Educacion Cusco
                            <strong>Oficina de Informatica</strong>.</p>
                            <p style="margin-bottom: 5px; line-height: 1;">
                            Todos los derechos reservados.
                        </p>
                    </div>
                </div>
            </div>
        </footer>
    </div>
@endsection
