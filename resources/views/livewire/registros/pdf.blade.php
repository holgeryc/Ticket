<!DOCTYPE html>
<html>

<head>
    <style>
        /* Estilos CSS para la tabla de registros */
        body {
            font-family: Arial, sans-serif;
        }

        h2 {
            text-align: center;
            margin-top: 50px;
            color: #333;
            margin: 0;
        }

        h1 {
            text-align: center;
            margin-top: 0px;
            color: #333;
        }

        table {
            width: 100%;
            margin-top: 20px;
            border: 1px solid #464343;
        }

        h4 {
            text-align: left;
            margin-top: 0px;
            color: #333;
            margin: 0;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #5f5a5a;
        }

        tr:nth-child(even) {
            background-color: #8b8787;
        }

        tr:hover {
            background-color: #f5f5f5;
        }

        .container {
            text-align: center;
        }

        .left-image {
            float: left;
            margin-right: 5px;
            width: 90px;
            height: 100px;
            /* Ajusta el margen derecho según tus necesidades */
        }

        .text-container {

            text-align: center;
        }
        .tabla-intercalada tr:nth-child(even) {
        background-color: rgb(151, 151, 151); /* Gris claro */
        }
        .tabla-intercalada tr:nth-child(odd) {
        background-color: white;
        }
    }
    </style>
    <title>Hoja Contable</title>
</head>

<body>

    <div class="container" style="text-align: left;">
        <img src="https://www.gereducusco.gob.pe/images/varios/logo2022_original.png" class="left-image">
        <div class="text-container">
            <h2 style="font-size: 20px;">MINISTERIO DE EDUCACIÓN</h2>
            <h2 style="font-size: 20px;">GERENCIA REGIONAL DE EDUCACIÓN CUSCO</h2>
            <h2 style="font-size: 20px;">{{ $oficinaSeleccionado }}</h2>
            <h2 style="font-size: 20px; text-align: center;">AUXILIAR ESTANDAR CAJA BANCOS</h2>
        </div>
        <h2 style="font-size: 20px; float: right;">MES DE {{ $mesSeleccionado }} DEL {{ $anioSeleccionado }}</h2>
        <h4 style="margin-top: 0;">CUENTA CORRIENTE N° {{ $oficinaCuentaCorriente }}</h4>
    </div>



    <table class="tabla-intercalada" style="font-size: 12px; table-layout: fixed; width: 100%; border-collapse: collapse;">
        <thead>
            <tr>
                <th style="height: 10px; width: 8%; background-color: rgb(111, 192, 224); text-align: center;">Fecha</th>
                <th style="height: 10px; width: 8%; background-color: rgb(111, 192, 224); text-align: center;">N°_Voucher</th>
                <th style="height: 10px; width: 8%; background-color: rgb(111, 192, 224); text-align: center;">N°_Cheque</th>
                <th style="height: 10px; width: 5%; background-color: rgb(111, 192, 224); text-align: center;">C_P</th>
                <th style="height: 10px; width: 21%; background-color: rgb(111, 192, 224); text-align: center;">Nombre y apellidos</th>
                <th style="height: 10px; width: 20%; background-color: rgb(111, 192, 224); text-align: center;">Detalle</th>
                <th style="height: 10px; width: 10%; background-color: rgb(111, 192, 224); text-align: center;">Entrada</th>
                <th style="height: 10px; width: 10%; background-color: rgb(111, 192, 224); text-align: center;">Salida</th>
                <th style="height: 10px; width: 10%; background-color: rgb(111, 192, 224); text-align: center;">Saldo</th>
                <!-- Agrega más encabezados según tu estcodigo_oficinatura de tabla -->
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="height: 10px; background-color: white; text-align: center;padding: 2px;"></td>
                <td style="height: 10px; background-color: white; text-align: center;padding: 2px;"></td>
                <td style="height: 10px; background-color: white; text-align: center;padding: 2px;"></td>
                <td style="height: 10px; background-color: white; text-align: center;padding: 2px;"></td>
                <td style="height: 10px; background-color: white; text-align: center;padding: 2px;"></td>
                <td style="height: 10px; background-color: white; text-align: center; padding: 2px;">SALDO ANTERIOR</td>
                <td style="height: 10px; background-color: white; text-align: center;padding: 2px;"></td>
                <td style="height: 10px; background-color: white; text-align: center;padding: 2px;"></td>
                <td style="height: 10px; background-color: white; text-align: right;padding: 2px;">{{ $saldoAnterior }}</td>
                <!-- Agrega más encabezados según tu estcodigo_oficinatura de tabla -->
            </tr>
            @foreach ($registros as $registro)
                <tr>
                    <td style="height: 10px; text-align: left; padding: 2px;">{{ $registro->FechaFormateada }}</td>
                    <td style="height: 10px; text-align: left; padding: 2px;">{{ $registro->N°_Voucher }}</td>
                    <td style="height: 10px; text-align: left; padding: 2px;">{{ $registro->N°_Cheque }}</td>
                    <td style="height: 10px; text-align: right; padding: 2px;">{{ $registro->C_P }}</td>
                    <td style="height: 10px; text-align: left; padding: 2px;">{{ $registro->Nombres_y_Apellidos }}</td>
                    <td style="height: 10px; text-align: left; padding: 2px;">{{ $registro->Detalle }}</td>
                    <td style="height: 10px; text-align: right; padding: 2px;">{{ $registro->Entrada }}</td>
                    <td style="height: 10px; text-align: right; padding: 2px;">{{ $registro->Salida }}</td>
                    <td style="height: 10px; text-align: right; padding: 2px;">{{ $registro->Saldo }}</td>
                    <!-- Agrega más columnas según tu estcodigo_oficinatura de tabla -->
                </tr>
            @endforeach
        </tbody>
    </table>
    <div style="background-color: rgba(90, 90, 94, 0.644); position: fixed; bottom: 0;margin-bottom:-25px; width: 100%; height: 30px; color: white;">
        <footer class="mt-1" style="margin-top: -10px;">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6"></div>
                    <div class="col-md-6" style="text-align: right;font-size: 10px">
                        <p style="margin-bottom: 0px; line-height: 1;">
                            <span style="display: inline-block; float: right;">
                                &copy; 2023 Gerencia Regional de Educación Cusco
                                <strong style="font-weight: bold;">Oficina de Centro de Cómputo</strong>.
                                Todos los derechos reservados.
                                <br>
                                Fecha y Hora:{{ $currentDateTime }}
                                <br>
                                URL:{{ $currentURL }}
                            </span>
                        </p>
                         <!-- Agregar el código QR -->

                    </div>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>