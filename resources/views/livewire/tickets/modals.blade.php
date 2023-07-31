<!-- Add Modal -->
<div wire:ignore.self class="modal fade" id="createDataModal" data-bs-backdrop="static" tabindex="-1" role="dialog"
    aria-labelledby="createDataModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createDataModalLabel">Ticket</h5>
                <button wire:click.prevent="cancel()" type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    
                    <div class="form-group">
                        <label for="Usuario">Usuario</label>
                        <input wire:model="Usuario" type="text" class="form-control" id="Usuario"
                               placeholder="Nombres y Apellidos" oninput="this.value = this.value.toUpperCase()">
                        @auth
                            @if (Auth::user()->Tipo === 'Personal_Geredu')
                                @error('Usuario')
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
                        <label for="Fecha_Inicio">Fecha_Inicio</label>
                        <input wire:model="Fecha_Inicio" type="date" class="form-control" id="Fecha_Inicio" placeholder="Fecha_Inicio" min="0">
                        @error('Fecha_Inicio')
                            <span class="error text-danger">{{ $message }}</span>
                        @else
                            <small class="form-text text-info">Campo obligatorio si Asignado esta vacio.</small>
                        @enderror
                    </div>
                    
                    

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close-btn" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" wire:click.prevent="store()" class="btn btn-primary">Guardar</button>

                <!-- <button type="button" onclick="Livewire.emit('store'); window.location.href='{{ route('generar-pdf', [$Oficina, $keyWord]) }}';  console.log('Evento store emitido');" class="btn btn-primary">Guardar</button> -->


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
                <h5 class="modal-title" id="updateModalLabel">Actualizar Ticket</h5>
                <button wire:click.prevent="cancel()" type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <input type="hidden" wire:model="selected_id">
                    <div class="form-group">
                        <label for="Usuario">Usuario</label>
                        <input wire:model="Usuario" type="text" class="form-control" id="Usuario"
                               placeholder="Nombres y Apellidos" oninput="this.value = this.value.toUpperCase()">
                        @auth
                            @if (Auth::user()->Tipo === 'Personal_Geredu')
                                @error('Usuario')
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
                        <label for="Fecha_Inicio">Fecha_Inicio</label>
                        <input wire:model="Fecha_Inicio" type="date" class="form-control" id="Fecha_Inicio" placeholder="Fecha_Inicio">
                        @error('Fecha_Inicio')
                            <span class="error text-danger">{{ $message }}</span>
                        @else
                            <small class="form-text text-info">Campo obligatorio si Asignado esta vacio.</small>
                        @enderror
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
