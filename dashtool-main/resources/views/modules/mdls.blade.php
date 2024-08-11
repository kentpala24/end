<div class="modal fade app-mdl" id="del-regs" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header bg-gray-300 p-3">
				<h5 class="modal-title" id="exampleModalLabel">
					<i class="bi bi-trash"></i> Eliminando registros
				</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<form id="form-up-edo" name="form-up-edo" action="post" method="post" enctype="multipart/form-data" accept-charset="utf-8">
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12 text-center" id="div-cnt-msg-up-edo"></div>
					</div>
					<input type="hidden" id="txt-list-dels" name="list" readonly="" value="0">
                    <input type="hidden" id="txt-mod-id" readonly="" value="0">
					<div class="row align-center justify-content-center">
						<div class="col-md-12 text-center">
							<p id="p-msg-del" class="text-danger fw-bold">¿Cuál acción desea aplicar para el registro actual?</p>
						</div>
                        <div class="col-md-6">
                            <span class="has-float-label">
                                <select id="slt-edo" name="slt_edo" class="form-select form-control" required="">
                                    <option value="" selected="" disabled="disabled">-- Seleccionar --</option>
                                    <option value="1" data-icon="bi bi-check-circle" data-clase="bg-success" data-txt="Activo">Activar</option>
                                    <option value="2" data-icon="fa fa-check-circle" data-clase="bg-success" data-txt="Desactivar">Desactivar</option>
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

<div class="modal fade" id="mdl-add-reg" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-xl" id="mdl-add-mod">
		<div class="modal-content">
			<form id="form-add-mod" class="form-add-mod" accept-charset="utf-8" enctype="multipart/form-data">
				<div class="modal-header bg-mdl-add p-3">
					<h5 class="modal-title"><i class="bi bi-plus-circle"></i> Agregando</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<input type="hidden" id="module_id" name="module_id" value="0" readonly="readonly">
					<div class="row">
						<div class="col-md-12 text-center" id="div-cnt-msg-add-mod"></div>
					</div>
					<div class="row">
						<div class="col-md-3 mb-3">
							<div class="input-group">
								<span class="has-float-label">
									<input type="text" class="form-control onlylettersspace" id="txt-nom" name="nom" placeholder=" " title="Nombre de sección ( sólo letras minúsculas, 4-30 caracteres)" required="" maxlength="30">
									<label for="txt-nom">Nombre:</label>
									<i class="bi bi-justify form-icon"></i>
								</span>
							</div>
						</div>
						<div class="col-md-4 mb-3">
							<div class="input-group">
								<span class="has-float-label">
									<input type="text" class="form-control " id="txt-desc-add" name="desc" placeholder=" " required="required">
									<label for="txt-desc-add">Descripción</label>
									<i class="bi bi-justify form-icon"></i>
								</span>
							</div>
						</div>
						<div class="col-md-3 mb-3">
							<div class="input-group">
								<span class="has-float-label">
									<input type="text" class="form-control lowercase" id="txt-icon" name="icon" placeholder=" " required="">
									<label for="txt-icon">Ícono</label>
									<i class="bi bi-boxes form-icon"></i>
								</span>
							</div>
						</div>

						<div class="col-md-2 mb-3">
							<div class="input-group">
								<span class="has-float-label">
									<select class="form-select" name="ype" id="slt-type" required="">
										<option value="module" selected="selected">Módulo</option>
										<option value="widget">Widget</option>
									</select>
									<label for="slt-color">Tipo</label>
								</span>
							</div>
						</div>

						<div class="col-md-5 mb-3 mb-3">
							<div class="input-group">
								<span class="has-float-label">
									<input type="text" class="form-control" id="txt-url" name="url_module" placeholder=" " required="">
									<label for="txt-url">URL</label>
									<i class="bi bi-link form-icon"></i>
								</span>
							</div>
						</div>

						<div class="col-md-2 mb-3">
							<div class="input-group">
								<span class="has-float-label">
									<select class="form-select" name="color" id="slt-color" required="">
										<option value="info" selected="">Info</option>
										<option value="danger">Danger</option>
										<option value="warning">Warning</option>
										<option value="success">Success</option>
										<option value="primary">Primary</option>
										<option value="secondary">Secondary</option>
										<option value="dark">Dark</option>
									</select>
									<label for="slt-color">Color sección</label>
								</span>
							</div>
						</div>

						<div class="col-md-2 mb-3">
							<div class="input-group">
								<span class="has-float-label">
									<select class="form-select" id="slt-show-add" name="show_on" required="">
										<option value="none">none</option>
										<option value="panel" selected="">panel</option>
										<option value="sidebar">sidebar</option>
										<option value="all">all</option>
										<option value="left">left</option>
										<option value="navbar">navbar</option>
										<option value="right">right</option>
									</select>
									<label for="slt-show-add">Mostrar en:</label>
								</span>
							</div>
						</div>

						<div class="col-md-3 mb-3 t-module">
							<div class="input-group">
								<span class="has-float-label">
									<select class="form-select" name="back_module_id" id="slt-back-id">
                                        <option value="" disabled="" selected="">-- Ninguno --</option>
                                        @if(isset($backs))
                                            @foreach($backs as $back)
                                                <option value="{{$back->id}}">{{$back->desc}}</option>
                                            @endforeach
                                        @endif
                                    </select>
									<label for="slt-back-id">Regresar atrás:</label>
								</span>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer p-2">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="btn-close-mdl-add-mod" name="btn-close-form-add-mod">
						<i class="bi bi-x-circle"></i> Cancelar
					</button>
					<button type="submit" class="btn btn-success" id="btn-add-mod">
						<i class="bi bi-check-circle"></i> Aceptar
					</button>
				</div>
			</form>
		</div>
	</div>
</div>


<div class="modal fade" id="mdl-up-sub-module" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content" id="mdl-up-info-sub">
			<form class="form-up-sub-module" accept-charset="utf-8" enctype="multipart/form-data">
				<div class="modal-header bg-gray-300 p-3">
					<h5 class="modal-title">
						<i class="bi bi-arrow-clockwise"></i> Actualizando
					</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
                    <div class="row">
                        <div id="div-cnt-sub-module" class="col-md-12">

                        </div>
                    </div>
				</div>
				<div class="modal-footer p-2">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="btn-close-mdl-up-sub">
						<i class="bi bi-x-circle"></i> Cancelar
					</button>
					<button type="submit" class="btn btn-success" id="btn-up-sub">
						<i class="bi bi-check-circle"></i> Aceptar
					</button>
				</div>
			</form>
		</div>
	</div>
</div>
