@extends('layouts.app')
@section('title', __('Registro'))
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Register') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <div class="row mb-3">
                                <label for="DNI" class="col-md-4 col-form-label text-md-end">{{ __('DNI') }}</label>

                                <div class="col-md-6">
                                    <input id="DNI" type="text" class="form-control" name="DNI"
                                        autocomplete="new-DNI">
                                    @error('DNI')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @else
                                        <small class="form-text text-info">Campo obligatorio,numerico y 8 digitos.</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="Nombres_y_Apellidos"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Nombres_y_Apellidos') }}</label>

                                <div class="col-md-6">
                                    <input id="Nombres_y_Apellidos" type="text" class="form-control"
                                        name="Nombres_y_Apellidos" autocomplete="new-Nombres_y_Apellidos">
                                    @error('Nombres_y_Apellidos')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @else
                                        <small class="form-text text-info">Campo obligatorio, se recomienda un nombre
                                            Real.</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="email"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Correo Electronico') }}</label>

                                <div class="col-md-6">
                                    <input id="email" type="text" class="form-control" name="email"
                                        autocomplete="new-email">
                                    @error('email')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @else
                                        <small class="form-text text-info">Campo obligatorio y de formato correo.</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="codigo_ug" class="col-md-4 col-form-label text-md-end">{{ __('Ugel') }}</label>
                                <div class="col-md-6">
                                    <select name="codigo_ug" class="form-control" id="codigo_ug">
                                        <option value="">Seleccionar Ugel</option>
                                        @foreach ($ugeles as $ugel)
                                            <option value="{{ $ugel->ug }}">{{ $ugel->nombre_ugel }}</option>
                                        @endforeach
                                    </select>
                                    @error('codigo_ug')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @else
                                        <small class="form-text text-info">Campo obligatorio si es de tipo Personal_Geredu.</small>
                                    @enderror
                                </div>
                            </div>


                            <div class="row mb-3">
                                <label for="password"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Contraseña') }}</label>

                                <div class="col-md-6">
                                    <input id="password" type="password"
                                        class="form-control @error('password') is-invalid @enderror" name="password"
                                        autocomplete="new-password">
                                    @error('password')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @else
                                        <small class="form-text text-info">Campo obligatorio,minimo 8 caracteres.</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password-confirm"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Confirmar Contraseña') }}</label>

                                <div class="col-md-6">
                                    <input id="password-confirm" type="password" class="form-control"
                                        name="password_confirmation" autocomplete="new-password">
                                    @error('password-confirm')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @else
                                        <small class="form-text text-info">Campo obligatorio,minimo 8 caracteres.</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Registrar') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
