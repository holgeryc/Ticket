<!-- Add Modal -->
<div wire:ignore.self class="modal fade" id="createDataModal" data-bs-backdrop="static" tabindex="-1" role="dialog"
    aria-labelledby="createDataModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createDataModalLabel">Crear Nuevo Registro</h5>
                <button wire:click.prevent="cancel()" type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="Cod_registro">Cod_registro</label>
                        <input wire:model="Cod_registro" type="date" class="form-control" id="Cod_registro"
                            placeholder="Cod_registro">
                        @error('Cod_registro')
                            <span class="error text-danger">{{ $message }}</span>
                        @else
                            <small class="form-text text-info">Campo obligatorio.</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="Nro_ticket">Nro ticket</label>
                        <input wire:model="Nro_ticket" type="text" class="form-control" id="Nro_ticket"
                            placeholder="Nro ticket" @if ($Asignado || $usuario) readonly @endif
                            oninput="if (this.value.trim() !== '') { document.getElementById('Asignado').readOnly = true; document.getElementById('usuario').readOnly = true; } else { document.getElementById('Asignado').readOnly = false; document.getElementById('usuario').readOnly = false; }">
                        @error('Nro_ticket')
                            <span class="error text-danger">{{ $message }}</span>
                        @else
                            <small class="form-text text-info">Campo obligatorio si Usuario esta vacio.</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="usuario">Usuario</label>
                        <input wire:model="usuario" type="text" class="form-control" id="usuario"
                            placeholder="Usuario" @if ($ruta_imagen || $Nro_ticket) readonly @endif
                            oninput="if (this.value.trim() !== '') { document.getElementById('ruta_imagen').readOnly = true; document.getElementById('Nro_ticket').readOnly = true; } else { document.getElementById('ruta_imagen').readOnly = false; document.getElementById('Nro_ticket').readOnly = false; }">
                        @error('usuario')
                            <span class="error text-danger">{{ $message }}</span>
                        @else
                            <small class="form-text text-info">Campo obligatorio si Nro ticket esta vacio.</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="C_P">C P</label>
                        <input wire:model="C_P" type="number" class="form-control" id="C_P" placeholder="C P" min="0">
                    </div>
                    <div class="form-group">
                        <label for="Nombres_y_Apellidos">Nombres y Apellidos</label>
                        <input wire:model="Nombres_y_Apellidos" type="text" class="form-control" id="Nombres_y_Apellidos"
                               placeholder="Nombres y Apellidos" oninput="this.value = this.value.toUpperCase()">
                        @auth
                            @if (Auth::user()->Tipo === 'Personal_Geredu')
                                @error('Nombres_y_Apellidos')
                                    <span class="error text-danger">{{ $message }}</span>
                                @else
                                    <small class="form-text text-info">Campo obligatorio, Datos de la persona que realizo el
                                        movimeinto economico.</small>
                                @enderror
                            @endif
                        @endauth
                    </div>
                    <div class="form-group">
                        <label for="Descripcion_problema">Descripcion_problema</label>
                        <input wire:model="Descripcion_problema" type="text" class="form-control" id="Descripcion_problema"
                            placeholder="Descripcion_problema" oninput="this.value = this.value.toUpperCase()">
                        @error('Descripcion_problema')
                            <span class="error text-danger">{{ $message }}</span>
                        @else
                            <small class="form-text text-info">Descripcion_problema del movimiento economico.</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="ruta_imagen">ruta_imagen</label>
                        <input wire:model="ruta_imagen" type="number" class="form-control" id="ruta_imagen" placeholder="ruta_imagen" min="0"
                        @if ($Asignado || $usuario) readonly @endif
                        oninput="if (this.value.trim() !== '') { document.getElementById('Asignado').readOnly = true; document.getElementById('usuario').readOnly = true; } else { document.getElementById('Asignado').readOnly = false; document.getElementById('usuario').readOnly = false; }">
                        @error('ruta_imagen')
                            <span class="error text-danger">{{ $message }}</span>
                        @else
                            <small class="form-text text-info">Campo obligatorio si Asignado esta vacio.</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="Asignado">Asignado</label>
                        <input wire:model="Asignado" type="number" class="form-control" id="Asignado" placeholder="Asignado" min="0"
                        @if ($ruta_imagen || $Nro_ticket) readonly @endif
                        oninput="if (this.value.trim() !== '') { document.getElementById('ruta_imagen').readOnly = true; document.getElementById('Nro_ticket').readOnly = true; } else { document.getElementById('ruta_imagen').readOnly = false; document.getElementById('Nro_ticket').readOnly = false; }">
                        @error('Asignado')
                            <span class="error text-danger">{{ $message }}</span>
                        @else
                            <small class="form-text text-info">Campo obligatorio si ruta_imagen esta vacio.</small>
                        @enderror
                    </div>
                    @auth
                        @if (Auth::user()->Tipo != 'Personal_Geredu')
                            <div class="form-group">
                                <label for="codigo_oficina">Oficina</label>
                                <select wire:model="codigo_oficina" class="form-control" id="codigo_oficina"
                                    placeholder="codigo_oficina Oficina">
                                    <option value="">Seleccionar Oficina</option>
                                    @foreach ($oficinas as $oficina)
                                        <option value="{{ $oficina->codigo_oficina }}">{{ $oficina->Nombre }}</option>
                                    @endforeach
                                </select>
                                @error('codigo_oficina')
                                    <span class="error text-danger">{{ $message }}</span>
                                @else
                                    <small class="form-text text-info">Campo obligatorio.</small>
                                @enderror
                            </div>
                        @endif
                    @endauth

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close-btn" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" wire:click.prevent="store()" class="btn btn-primary">Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div wire:ignore.self class="modal fade" id="updateDataModal" data-bs-backdrop="static" tabindex="-1"
    role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateModalLabel">Actualizar Registro</h5>
                <button wire:click.prevent="cancel()" type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <input type="hidden" wire:model="selected_id">
                    <div class="form-group">
                        <label for="Cod_registro">Cod_registro</label>
                        <input wire:model="Cod_registro" type="date" class="form-control" id="Cod_registro"
                            placeholder="Cod_registro">
                        @error('Cod_registro')
                            <span class="error text-danger">{{ $message }}</span>
                        @else
                            <small class="form-text text-info">Campo obligatorio.</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="Nro_ticket">Nro ticket</label>
                        <input wire:model="Nro_ticket" type="text" class="form-control" id="Nro_ticket"
                            placeholder="Nro ticket" @if ($Asignado || $usuario) readonly @endif
                            oninput="if (this.value.trim() !== '') { document.getElementById('Asignado').readOnly = true; document.getElementById('usuario').readOnly = true; } else { document.getElementById('Asignado').readOnly = false; document.getElementById('usuario').readOnly = false; }">
                        @auth
                            @if (Auth::user()->Tipo === 'Personal_Geredu')
                                @error('Nro_ticket')
                                    <span class="error text-danger">{{ $message }}</span>
                                @else
                                    <small class="form-text text-info">Campo obligatorio si Usuario esta vacio.</small>
                                @enderror
                            @endif
                        @endauth
                    </div>
                    <div class="form-group">
                        <label for="usuario">Usuario</label>
                        <input wire:model="usuario" type="text" class="form-control" id="usuario"
                            placeholder="Usuario" @if ($ruta_imagen || $Nro_ticket) readonly @endif
                            oninput="if (this.value.trim() !== '') { document.getElementById('ruta_imagen').readOnly = true; document.getElementById('Nro_ticket').readOnly = true; } else { document.getElementById('ruta_imagen').readOnly = false; document.getElementById('Nro_ticket').readOnly = false; }">
                        @auth
                            @if (Auth::user()->Tipo === 'Personal_Geredu')
                                @error('usuario')
                                    <span class="error text-danger">{{ $message }}</span>
                                @else
                                    <small class="form-text text-info">Campo obligatorio si Nro ticket esta vacio.</small>
                                @enderror
                            @endif
                        @endauth
                    </div>
                    
                    <!-- <div class="form-group">
                        <label for="Nombres_y_Apellidos">Nombres y Apellidos</label>
                        <input wire:model="Nombres_y_Apellidos" type="text" class="form-control" id="Nombres_y_Apellidos"
                               placeholder="Nombres y Apellidos" oninput="this.value = this.value.toUpperCase()">
                        @auth
                            @if (Auth::user()->Tipo === 'Personal_Geredu')
                                @error('Nombres_y_Apellidos')
                                    <span class="error text-danger">{{ $message }}</span>
                                @else
                                    <small class="form-text text-info">Campo obligatorio, Datos de la persona que realizo el
                                        movimeinto economico.</small>
                                @enderror
                            @endif
                        @endauth
                    </div> -->
                    <div class="form-group">
                        <label for="Descripcion_problema">Descripcion_problema</label>
                        <input wire:model="Descripcion_problema" type="text" class="form-control" id="Descripcion_problema"
                            placeholder="Descripcion_problema" oninput="this.value = this.value.toUpperCase()">
                        @error('Descripcion_problema')
                            <span class="error text-danger">{{ $message }}</span>
                        @else
                            <small class="form-text text-info">Descripcion_problema del movimiento economico.</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="ruta_imagen">ruta_imagen</label>
                        <input wire:model="ruta_imagen" type="number" class="form-control" id="ruta_imagen" placeholder="ruta_imagen" min="0"
                        @if ($Asignado || $usuario) readonly @endif
                        oninput="if (this.value.trim() !== '') { document.getElementById('Asignado').readOnly = true; document.getElementById('usuario').readOnly = true; } else { document.getElementById('Asignado').readOnly = false; document.getElementById('usuario').readOnly = false; }">
                        @auth
                            @if (Auth::user()->Tipo === 'Personal_Geredu')
                                @error('ruta_imagen')
                                    <span class="error text-danger">{{ $message }}</span>
                                @else
                                    <small class="form-text text-info">Campo obligatorio si Asignado esta vacio.</small>
                                @enderror
                            @endif
                        @endauth
                    </div>
                    <div class="form-group">
                        <label for="Asignado">Asignado</label>
                        <input wire:model="Asignado" type="number" class="form-control" id="Asignado" placeholder="Asignado" min="0"
                        @if ($ruta_imagen || $Nro_ticket) readonly @endif
                        oninput="if (this.value.trim() !== '') { document.getElementById('ruta_imagen').readOnly = true; document.getElementById('Nro_ticket').readOnly = true; } else { document.getElementById('ruta_imagen').readOnly = false; document.getElementById('Nro_ticket').readOnly = false; }">
                        @auth
                            @if (Auth::user()->Tipo === 'Personal_Geredu')
                                @error('Asignado')
                                    <span class="error text-danger">{{ $message }}</span>
                                @else
                                    <small class="form-text text-info">Campo obligatorio si ruta_imagen esta vacio.</small>
                                @enderror
                            @endif
                        @endauth
                    </div>
                    @auth
                        @if (Auth::user()->Tipo != 'Personal_Geredu')
                            <div class="form-group">
                                <label for="codigo_oficina">Oficina</label>
                                <select wire:model="codigo_oficina" class="form-control" id="codigo_oficina"
                                    placeholder="codigo_oficina Oficina">
                                    <option value="">Seleccionar Oficina</option>
                                    @foreach ($oficinas as $oficina)
                                        <option value="{{ $oficina->codigo_oficina }}">{{ $oficina->Nombre }}</option>
                                    @endforeach
                                </select>
                                @error('codigo_oficina')
                                    <span class="error text-danger">{{ $message }}</span>
                                @else
                                    <small class="form-text text-info">Campo obligatorio.</small>
                                @enderror
                            </div>
                        @endif
                        @if (Auth::user()->Tipo === 'Administrador')
                            <div class="form-group">
                                <label for="Activado">Activado</label>
                                <input wire:model="Activado" type="checkbox" class="form-check-input" id="Activado"
                                    placeholder="Activado">
                                @error('Activado')
                                    <span class="error text-danger">{{ $message }}</span>
                                @else
                                    <small class="form-text text-info">Activar y Descactivar Registro.</small>
                                @enderror
                            </div>
                        @endif
                    @endauth
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" wire:click.prevent="cancel()" class="btn btn-secondary"
                    data-bs-dismiss="modal">Cerrar</button>
                <button type="button" wire:click.prevent="update()" class="btn btn-primary">Guardar</button>
            </div>
        </div>
    </div>
</div>
