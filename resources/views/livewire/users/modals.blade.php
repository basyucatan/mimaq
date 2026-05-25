<div x-data="{ abierto: true }" x-show="abierto" class="modal fade show d-block" style="background: rgba(0,0,0,0.5);">
    <div x-data="modalDraggable" class="modal-dialog" style="top: 50px; left: 50%; transform: translateX(-50%); width: 100%; max-width: 500px;">
        <div class="modal-content shadow-lg">
            <div class="modal-header bg-light" style="cursor: move; user-select: none;" @mousedown="iniciarArrastre($event)">
                <h5 class="modal-title">
                    {{ $selected_id ? 'Editar Usuario' : 'Nuevo Usuario' }}
                </h5>
                <button type="button" class="btn-close" @click="abierto = false; window.location.href='{{URL_BASE}}users'"></button>
            </div>
            
            <form action="{{URL_BASE}}users/save" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="selected_id" value="{{$selected_id}}">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label font-weight-bold">Nombre</label>
                            <input type="text" name="name" class="form-control" value="{{$name}}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Teléfono</label>
                            <input type="text" name="telefono" class="form-control" value="{{$telefono}}">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{$email}}" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Contraseña {{ $selected_id ? '(Opcional)' : '' }}</label>
                            <input type="password" name="password" class="form-control" {{ $selected_id ? '' : 'required' }}>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" @click="abierto = false; window.location.href='{{URL_BASE}}users'">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>