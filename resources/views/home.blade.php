@extends('layouts.app')
@section('title', __('Home'))
@section('content')
    <div class="container-fluid" >
        <div class="row justify-content-center" >
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5><span class="text-center fa fa-home"></span>GERENCIA REGIONAL DE EDUACION CUSCO</h5>
                    </div>
                    <img class="mr-auto" target="_blank"
                        src="https://lh3.googleusercontent.com/p/AF1QipMZim4lz5ECi6WkOaT4XegO8vYbhTVf8OgYfBs6=s680-w680-h510">
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
    <div style="position: absolute;">
        <center style="font-size: 8px; opacity: 0.1;color:white;">JAFET CALEB ROJAS GARAY</center>
        <center style="font-size: 8px; opacity: 0.1;color:white;">HOLGER ALFREDO YUPANQUI CARRILLO</center>
    </div>
@endsection
