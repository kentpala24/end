<div class="modal fade app-mdl" id="del-regs" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header bg-mdl-del p-3">
				<h5 class="modal-title" id="exampleModalLabel">
					<i class="bi bi-trash"></i> Eliminando registros
				</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<form id="form-up-edo" name="form-up-edo" action="post" class="form-search" method="post" enctype="multipart/form-data" accept-charset="utf-8">
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12 text-center" id="div-cnt-msg-up-edo"></div>
					</div>
					<input type="hidden" id="txt-list-dels" name="list" readonly="" value="0">
					<div class="row align-center justify-content-center">
						<div class="col-md-12 text-center">
							<p id="p-msg-del" class="text-danger fw-bold">¿Cuál acción desea aplicar para el registro actual?</p>
						</div>
                        <div class="col-md-6">
                            <span class="has-float-label">
                                <select id="slt-edo" name="slt_edo" class="form-select form-control" required>
                                    <option value="" selected="" disabled="disabled">-- Seleccionar --</option>
                                    <option value="1" data-icon="bi bi-check-circle" data-clase="bg-success" data-txt="Activo">Activar</option>
                                    <option value="2" data-icon="fa fa-check-circle" data-clase="bg-success" data-txt="Bloquear">Bloquear</option>
                                    <option value="3" data-icon="bi bi-check-circle" data-clase="bg-warning" data-txt="Banear">Banear</option>
                                    <option value="0" data-icon="bi bi-trash" data-clase="bg-danger" data-txt="Eliminar">Eliminar</option>
                                </select>
                                <label for="slt-edo">Acción</label>
                            </span>
                        </div>
					</div>
				</div>
				<div class="modal-footer p-2">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="btn-close-mdl-up-edo">
						<i class="bi bi-x-circle"></i> Cerrar
					</button>
					<button type="submit" class="btn btn-success" id="btn-up-edo">
						<i class="bi bi-check-circle"></i> Aceptar
					</button>
				</div>
			</form>
		</div>
	</div>
</div>
