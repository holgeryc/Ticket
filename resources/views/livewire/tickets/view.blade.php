@section('title', __('Tickets'))
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <h4><i class="fa fa-bars text-info"></i>
                                Tickets</h4>
                        </div>
                        @if (session()->has('message'))
                            <div wire:poll.4s class="btn btn-sm btn-success" style="margin-top:0px; margin-bottom:0px;">
                                {{ session('message') }} </div>
                        @endif
                        <div>
                            <input wire:model='keyWord' type="text" class="form-control" name="search"
                                id="search" placeholder="Buscar Tickets">
                        </div>
                        @auth
                            @if (Auth::user()->Tipo != 'Personal_Geredu')
                                <select wire:model="ugelSeleccionado" class="form-control" style="width: 200px;">
                                    <option value="">Seleccionar oficina</option>
                                    @foreach ($oficinas as $oficina)
                                        <option value="{{ $oficina->codigo_oficina }}">{{ $oficina->nombre }}</option>
                                    @endforeach
                                </select>
                            @endif
                        @endauth
                        <select wire:model="mesSeleccionado" class="form-control" style="width: 200px;">
                            <option value="">Seleccione meses</option>
                            <option value="01">Enero</option>
                            <option value="02">Febrero</option>
                            <option value="03">Marzo</option>
                            <option value="04">Abril</option>
                            <option value="05">Mayo</option>
                            <option value="06">Junio</option>
                            <option value="07">Julio</option>
                            <option value="08">Agosto</option>
                            <option value="09">Septiembre</option>
                            <option value="10">Octubre</option>
                            <option value="11">Noviembre</option>
                            <option value="12">Diciembre</option>
                        </select>
                        <input wire:model='anioSeleccionado' type="text" class="form-control" style="width: 200px;"
                            placeholder="Ingrese Año">
                        <div>
                            <a href="{{ route('generar-pdf', [$ugelSeleccionado, $keyWord]) }}"
                                class="btn btn-sm btn-info" target="_blank">
                                <i class="fa fa-file-pdf-o"></i> Generar PDF
                            </a>
                        </div>
                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif
                        <!-- @auth
                            @if (Auth::user()->Tipo === 'Administrador' || Auth::user()->Tipo === 'Centro_computo')
                                <div class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#createDataModal">
                                    <i class="fa fa-plus"></i> Agregar Tickets
                                </div>
                            @endif
                        @endauth -->

                    </div>
                </div>

                <div class="card-body">
                    @include('livewire.tickets.modals')
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead class="thead">
                                <tr>
                                    <th>Nro Ticket</th>
                                    <th>Usuario</th>
                                    <th>Ugel</th>
                                    <th>Oficina</th>
                                    <th>Descripcion_problema</th>
                                    <th>Encargado</th>
                                    <th>Estado</th>
                                    <th>Solucion</th>
                                    <th>Fecha_Inicio</th>
                                    <th>Fecha_Final</th>
                                    <th>Activado</th>
                                    @auth
                                        @if (Auth::user()->Tipo === 'Administrador' || Auth::user()->Tipo === 'Personal_Geredu')
                                            <td>Acciones</td>
                                        @endif
                                    @endauth
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($registros as $row)
                                    <tr>
                                        <td>{{ $row->Ticket }}</td>
                                        <td>{{ $row->Nombres_y_Apellidos }}</td>
                                        <td>{{ $row->nombre_ugel }}</td>
                                        <td>{{ $row->nombre}}</td>
                                        <td>{{ $row->Descripcion_problema }}</td>
                                        <td>{{ $row->Encargado }}</td>
                                        <td>{{ $row->Estado }}</td>
                                        <td>{{ $row->Solucion }}</td>
                                        <td>{{ $row->Fecha_Inicio }}</td>
                                        <td>{{ $row->Fecha_Final }}</td>
                                        <td>{{ $row->Activado }}</td>
                                        @auth
                                            @if (Auth::user()->Tipo === 'Administrador'|| Auth::user()->Tipo === 'Centro_computo')
                                                <td width="90">
                                                    <div class="dropdown">
                                                        <a class="btn btn-sm btn-secondary dropdown-toggle" href="#"
                                                            role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                            Acciones
                                                        </a>
                                                        <ul class="dropdown-menu">
                                                            <li><a data-bs-toggle="modal" data-bs-target="#updateDataModal"
                                                                    class="dropdown-item"
                                                                    wire:click="edit({{ $row->id }})"><i
                                                                        class="fa fa-edit"></i> Editar </a></li>
                                                            <li><a class="dropdown-item"
                                                                    onclick="confirm('Confirm Delete Registro id {{ $row->id }}? \nDeleted Tickets cannot be recovered!')||event.stopImmediatePropagation()"
                                                                    wire:click="destroy({{ $row->id }})"><i
                                                                        class="fa fa-trash"></i> Eliminar </a></li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            @endif
                                            @if (Auth::user()->Tipo === 'Personal_Geredu' && $row->Activado)
                                                <td width="90">
                                                    <div class="dropdown">
                                                        <a class="btn btn-sm btn-secondary dropdown-toggle" href="#"
                                                            role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                            Acciones
                                                        </a>
                                                        <ul class="dropdown-menu">
                                                            <li><a data-bs-toggle="modal" data-bs-target="#updateDataModal"
                                                                    class="dropdown-item"
                                                                    wire:click="edit({{ $row->id }})"><i
                                                                        class="fa fa-edit"></i> Editar </a></li>
                                                            <li><a class="dropdown-item"
                                                                    onclick="confirm('Confirm Delete Registro id {{ $row->id }}? \nDeleted Tickets cannot be recovered!')||event.stopImmediatePropagation()"
                                                                    wire:click="destroy({{ $row->id }})"><i
                                                                        class="fa fa-trash"></i> Eliminar </a></li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            @endif
                                        @endauth
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-center" colspan="100%">No data Found </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
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
                        Copyright © 2023 Gerencia Regional de Educación Cusco
                        <strong>Oficina de Centro de Cómputo</strong>.</p>
                        <p style="margin-bottom: 5px; line-height: 1;">
                        Todos los derechos reservados.
                    </p>
                </div>
            </div>
        </div>
    </footer>
</div>
