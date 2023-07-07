<!-- Add Modal -->
<div wire:ignore.self class="modal fade" id="createDataModal" data-bs-backdrop="static" tabindex="-1" role="dialog"
    aria-labelledby="createDataModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createDataModalLabel">Crear Nuevo Usuario</h5>
                <button wire:click.prevent="cancel()" type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="DNI"></label>
                        <input wire:model="DNI" type="text" class="form-control" id="DNI" placeholder="Dni">
                        @error('DNI')
                            <span class="error text-danger">{{ $message }}</span>
                        @else
                            <small class="form-text text-info">Campo obligatorio,numerico y 8 digitos.</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="Nombres_y_Apellidos"></label>
                        <input wire:model="Nombres_y_Apellidos" type="text" class="form-control"
                            id="Nombres_y_Apellidos" placeholder="Nombres Y Apellidos">
                        @error('Nombres_y_Apellidos')
                            <span class="error text-danger">{{ $message }}</span>
                        @else
                            <small class="form-text text-info">Campo obligatorio, se recomienda un nombre Real.</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="email"></label>
                        <input wire:model="email" type="text" class="form-control" id="email"
                            placeholder="Email">
                        @error('email')
                            <span class="error text-danger">{{ $message }}</span>
                        @else
                            <small class="form-text text-info">Campo obligatorio y de formato correo.</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="Tipo"></label>
                        <select wire:model="Tipo" class="form-control" id="Tipo" placeholder="Tipo">
                            <option value="">Seleccion Tipo</option>
                            <option value="Personal_Geredu">Personal_Geredu</option>
                            <option value="Centro_computo">Centro_computo</option>
                            <option value="Administrador">Administrador</option>
                        </select>
                        @error('Tipo')
                            <span class="error text-danger">{{ $message }}</span>
                        @else
                            <small class="form-text text-info">De no seleccionar, sera de tipo Personal_Geredu.</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="codigo_oficina"></label>
                        <select wire:model="codigo_oficina" class="form-control" id="codigo_oficina"
                            placeholder="codigo_oficina Oficina">
                            @error('codigo_oficina')
                                <span class="error text-danger">{{ $message }}</span>
                            @enderror>
                            <option value="">Seleccionar Oficina</option>
                            @foreach ($oficinas as $oficina)
                                <option value="{{ $oficina->codigo_oficina }}">{{ $oficina->nombre }}</option>
                            @endforeach
                        </select>
                        @error('codigo_oficina')
                            <span class="error text-danger">{{ $message }}</span>
                        @else
                            <small class="form-text text-info">Campo obligatorio si es de tipo Personal_Geredu.</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password"></label>
                        <input wire:model="password" type="password" class="form-control" id="password"
                            placeholder="Contraseña">
                        @error('password')
                            <span class="error text-danger">{{ $message }}</span>
                        @else
                            <small class="form-text text-info">Campo obligatorio,minimo 8 caracteres.</small>
                        @enderror
                    </div>

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
<div wire:ignore.self class="modal fade" id="updateDataModal" data-bs-backdrop="static" tabindex="-1" role="dialog"
    aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateModalLabel">Actualizar Usuario</h5>
                <button wire:click.prevent="cancel()" type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="DNI">DNI</label>
                        <input wire:model="DNI" type="text" class="form-control" id="DNI"
                            placeholder="Dni" readonly>
                    </div>
                    <div class="form-group">
                        <label for="Nombres_y_Apellidos">Nombres y Apellidos</label>
                        <input wire:model="Nombres_y_Apellidos" type="text" class="form-control"
                            id="Nombres_y_Apellidos" placeholder="Nombres Y Apellidos">
                        @error('Nombres_y_Apellidos')
                            <span class="error text-danger">{{ $message }}</span>
                        @else
                            <small class="form-text text-info">Campo obligatorio, se recomienda un nombre Real.</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="email">Correo</label>
                        <input wire:model="email" type="text" class="form-control" id="email"
                            placeholder="Correo">
                        @error('email')
                            <span class="error text-danger">{{ $message }}</span>
                        @else
                            <small class="form-text text-info">Campo obligatorio y de formato correo.</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="Tipo">Tipo de Usuario</label>
                        <select wire:model="Tipo" class="form-control" id="Tipo" placeholder="Tipo">
                            <option value="">Seleccion Tipo</option>
                            <option value="Personal_Geredu">Personal_Geredu</option>
                            <option value="Centro_computo">Centro_computo</option>
                            <option value="Administrador">Administrador</option>
                        </select>
                        @error('Tipo')
                            <span class="error text-danger">{{ $message }}</span>
                        @else
                            <small class="form-text text-info">De no seleccionar, sera de tipo Personal_Geredu.</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="codigo_oficina">Oficina Asociado</label>
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
                            <small class="form-text text-info">Campo obligatorio si es de tipo Personal_Geredu.</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="Activado">Activado</label>
                        <input wire:model="Activado" type="checkbox" class="form-check-input" id="Activado"
                            placeholder="Activado">
                        @error('Activado')
                            <span class="error text-danger">{{ $message }}</span>
                        @else
                            <small class="form-text text-info">Activar o Desactivar al Usuario.</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="password">
                            <input wire:model="NuevaContraseña" type="checkbox">
                            Nueva Contraseña</label>
                        <input wire:model="password" type="password" class="form-control" id="password"
                            placeholder="Contraseña" {{ $NuevaContraseña ? '' : 'readonly disabled' }}>
                        @if ($NuevaContraseña)
                            @error('password')
                                <span class="error text-danger">{{ $message }}</span>
                            @else
                                <small class="form-text text-info">Campo obligatorio,minimo 8 caracteres.</small>
                            @enderror
                        @endif
                    </div>

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

<script>
    window.addEventListener('beforeunload', function() {
        document.getElementById("createDataModal").reset();
    });
</script>
