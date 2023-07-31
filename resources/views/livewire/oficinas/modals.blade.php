<!-- Add Modal -->
<div wire:ignore.self class="modal fade" id="createDataModal" data-bs-backdrop="static" tabindex="-1" role="dialog"
    aria-labelledby="createDataModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createDataModalLabel">Crear Nueva Oficina</h5>
                <button wire:click.prevent="cancel()" type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="codigo_oficina"></label>
                        <input wire:model="codigo_oficina" type="text" class="form-control" id="codigo_oficina" placeholder="codigo_oficina">
                        @error('codigo_oficina')
                            <span class="error text-danger">{{ $message }}</span>
                        @else
                            <small class="form-text text-info">Campo obligatorio, numerico y 11 digitos.</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="nombre"></label>
                        <input wire:model="nombre" type="text" class="form-control" id="nombre"
                            placeholder="nombre">
                        @error('nombre')
                            <span class="error text-danger">{{ $message }}</span>
                        @else
                            <small class="form-text text-info">Campo obligatorio, se recomienda el nombre completo del
                                Oficina.</small>
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
                <h5 class="modal-title" id="updateModalLabel">Actualizar Oficina</h5>
                <button wire:click.prevent="cancel()" type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="codigo_oficina"></label>
                        <input wire:model="codigo_oficina" type="text" class="form-control" id="codigo_oficina" placeholder="codigo_oficina"
                            readonly disabled>
                        @error('codigo_oficina')
                            <span class="error text-danger">{{ $message }}</span>
                        @else
                            <small class="form-text text-info">Campo obligatorio, numerico y 11 digitos.</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="nombre"></label>
                        <input wire:model="nombre" type="text" class="form-control" id="nombre"
                            placeholder="nombre">
                        @error('nombre')
                            <span class="error text-danger">{{ $message }}</span>
                        @else
                            <small class="form-text text-info">Campo obligatorio, se recomienda el nombre completo de la oficina.</small>
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
