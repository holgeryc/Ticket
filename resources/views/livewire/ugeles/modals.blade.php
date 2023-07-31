<!-- Add Modal -->
<div wire:ignore.self class="modal fade" id="createDataModal" data-bs-backdrop="static" tabindex="-1" role="dialog"
    aria-labelledby="createDataModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createDataModalLabel">Crear Nueva ugel</h5>
                <button wire:click.prevent="cancel()" type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="ug"></label>
                        <input wire:model="ug" type="text" class="form-control" id="ug" placeholder="ug">
                        @error('ug')
                            <span class="error text-danger">{{ $message }}</span>
                        @else
                            <small class="form-text text-info">Campo obligatorio, numerico y 11 digitos.</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="nombre_ugel"></label>
                        <input wire:model="nombre_ugel" type="text" class="form-control" id="nombre_ugel"
                            placeholder="nombre_ugel">
                        @error('nombre_ugel')
                            <span class="error text-danger">{{ $message }}</span>
                        @else
                            <small class="form-text text-info">Campo obligatorio, se recomienda el nombre_ugel completo del
                                ugel.</small>
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
                <h5 class="modal-title" id="updateModalLabel">Actualizar ugel</h5>
                <button wire:click.prevent="cancel()" type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="ug"></label>
                        <input wire:model="ug" type="text" class="form-control" id="ug" placeholder="ug"
                            readonly disabled>
                        @error('ug')
                            <span class="error text-danger">{{ $message }}</span>
                        @else
                            <small class="form-text text-info">Campo obligatorio, numerico y 11 digitos.</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="nombre_ugel"></label>
                        <input wire:model="nombre_ugel" type="text" class="form-control" id="nombre_ugel"
                            placeholder="nombre_ugel">
                        @error('nombre_ugel')
                            <span class="error text-danger">{{ $message }}</span>
                        @else
                            <small class="form-text text-info">Campo obligatorio, se recomienda el nombre_ugel completo de la ugel.</small>
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
