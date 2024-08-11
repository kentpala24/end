var limite 		= 10;
var filter 		= 0;
var order 		= "desc";
var order_by 	= "id";
var search 		= "";
var url 		= "";
var reg 		= 0;
var fromtable 	= "modules";
var table;
var toolbarBase = document.querySelector('[data-kt-user-table-toolbar="base"]');
var toolbarSelected = document.querySelector('[data-kt-user-table-toolbar="selected"]');
var selectedCount = document.querySelector('[data-kt-user-table-select="selected_count"]');
const deleteSelected = document.querySelector('[data-kt-user-table-select="delete_selected"]');
// Detect checkboxes state & count
let checkedState = false;
let count = 0;
var list = [];

$('.dropdown-limit').find('a').click(function(e) {
	limite = $(this).data("edo");
	load(1);
	e.preventDefault();
});

$('.dropdown-edo').find('a').click(function(e) {
	filter = $(this).data("edo");
	load(1);
	e.preventDefault();
});

$('.dropdown-users').find('a').click(function(e) {
	user = $(this).data("edo");
	load(1);
	e.preventDefault();
});

$(document).on("submit", ".form-search", function (event) {
	var parametros = $(this).serialize();
	search = $('#txt-search').val();
	load(1);
	event.preventDefault();
});

function load(page) {
	$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
	$.ajax({
		type: 'POST',
		url: base_url + '/loadModules',
		method: 'POST',
		dataType: 'JSON',
		data: {
			page: 	page,
			search: search,
			filter: filter,
			limite: limite,
			url: 	url,
			order: 	order,
			order_by: 	order_by,
		},
		beforeSend: function(objeto) {
			$('.btn-search').html('<span class="spinner-border spin-x" role="status" aria-hidden="true"></span> Buscando...');
			$("#div-cnt-load").html('<div class="text-center alert alert-dark" role="alert">' +
				'<span class="spinner-border spin-x" role="status" aria-hidden="true"></span> Buscando...</div>');
		},
		success: function(res) {
			$('#div-cnt-load').html(res.data);
			$('#h5-cnt-total').html('Resultados: '+res.total);
			$('.btn-search').html('<i class="bi bi-search"></i>');
            checkedState = false;
            count = 0;
            list = [];
		},
		error: function(data) {
			$(".btn-search").html('<i class="bi bi-search"></i>');
			$("#div-cnt-load").html('<div class="text-center alert alert-danger" role="alert"><i class="fas fa-exclamation-circle"></i> Error interno, intenta más tarde.</div>');
		}
	});
}

$(document).on("click", ".table th.th-link", function() {
	if (order == "asc") {
		order = "desc";
	} else {
		order = "asc";
	}
	order_by = $(this).attr("data-field");
	load(1);
});

$(document).on("click", ".btn-add-module-id", function() {
	$('#module_id').val($(this).attr("data-id"));
});

$("#form-add-mod").submit(function(event) {
	var parametros = $(this).serialize();
	$.ajax({
		type: 		"POST",
		dataType: 	"JSON",
		method: 	"POST",
		url: 		base_url + "/storeModule",
		data: 		parametros,
		beforeSend: function(objeto) {
			$('#btn-add-mod').attr("disabled", true);
			$("#btn-add-mod").html('<span class="spinner-border spin-x" role="status" aria-hidden="true"></span> Agregando');
		},
		success: function(datos) {
			$("#btn-add-mod").html('<i class="bi bi-check-circle"></i> Aceptar');
			$('#btn-add-mod').attr("disabled", false);
			if (datos.tipo == "success") {
				$("#btn-close-mdl-add-mod").trigger("click");
				
				if (reg>0) {
					loadSubModules(reg);
				}else{
					load(1);
				}
				$("#form-add-mod")[0].reset();
				notifyMsg(datos.msg, '#', datos.tipo, '');
			}else{
                if(datos.errors){
                    jQuery.each(datos.errors, function(key, value){
						notifyMsg(value, '#', 'danger', 'mdl-add-mod');
                    });
                }else{
					notifyMsg(datos.msg, '#', datos.tipo, 'mdl-add-mod');
                }
            }
		},
		error: function(data) {
			$("#btn-add-mod").html('<i class="bi bi-check-circle"></i> Aceptar');
			$("#btn-add-mod").attr("disabled", false);
			notifyMsg('Error interno, intenta más tarde.', '#', 'danger', 'mdl-add-mod');
		}
	});
	event.preventDefault();
});


function loadSubModules(reg) {
	$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
	$.ajax({
		url: 		base_url + "/loadSubModules",
		method: 	"POST",
		dataType: 	"JSON",
		type: 		"POST",
		data: {
			reg: reg,
		},
		beforeSend: function (objeto) {
			$("#div-cnt-modules").html('<div class="alert alert-dark text-center" role="alert">'+
				'<span class="spinner-border spin-x" role="status" aria-hidden="true"></span> Cargando</div>');
		},
		success: function (data) {
			$("#div-cnt-modules").html(data.data);
		},
		error: function (response) {
			$("#div-cnt-modules").html('<div class="alert alert-danger text-center" role="alert"><i class="bi bi-x-circle"></i> '+
				'Error interno, intenta más tarde.</div>');
		}
	});
};

function loadInfoReg(reg) {
	$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
	$.ajax({
		url: 		base_url + "/loadInfoModule",
		method: 	"POST",
		dataType: 	"JSON",
		type: 		"POST",
		data: {
			reg: reg,
		},
		beforeSend: function (objeto) {
			$("#div-cnt-profile").html('<div class="alert alert-dark text-center" role="alert">'+
				'<span class="spinner-border spin-x" role="status" aria-hidden="true"></span> Cargando</div>');
		},
		success: function (data) {
			$("#div-cnt-profile").html(data.results);
		},
		error: function (response) {
			$("#div-cnt-profile").html('<div class="alert alert-danger text-center" role="alert"><i class="bi bi-x-circle"></i> '+
				'Error interno, intenta más tarde.</div>');
		}
	});
};

$(document).on("submit", ".form-up-reg", function (e) {
	var parametros = $(this).serialize();
	$.ajax({
		type: "POST",
		url: base_url + "/upInfoModule",
		data: parametros,
		dataType: "json",
		beforeSend: function (objeto) {
            $("#btn-up-reg").attr("disabled", true);
			$("#btn-up-reg").html('<span class="spinner-border spin-x" role="status" aria-hidden="true"></span> Actualizando');
		},
		success: function (datos) {
			$("#btn-up-reg").html('<i class="bi bi-check-circle"></i> Actualizar');
			$("#btn-up-reg").attr("disabled", false);
			if (datos.tipo == 'success') {
				loadInfoReg(reg);
			}
            if(datos.errors){
                jQuery.each(datos.errors, function(key, value){
					notifyMsg(value, '#', 'danger', '');
                });
            }else{
				notifyMsg(datos.msg, '#', datos.tipo, '');
            }
		},
		error: function (data) {
			$("#btn-up-reg").html('<i class="bi bi-check-circle"></i> Actualizar');
			$("#btn-up-reg").attr("disabled", false);
			notifyMsg('Error interno, intenta más tarde', '#', 'danger', '');
		}
	});
	e.preventDefault();
});



$(document).on("click", ".mdl-up-reg", function() {
	var id = $(this).data('idreg');
	$("#div-cnt-sub-module").html("");
	loadInfoSubModule(id);
});

function loadInfoSubModule(mod) {
	$.ajax({
		url: 		base_url + "/loadInfoSubModule",
		method: 	"POST",
		dataType: 	"JSON",
		type: 		"POST",
		data: {
			reg: mod,
		},
		beforeSend: function (objeto) {
			$("#div-cnt-sub-module").html('<div class="alert alert-info alert-dismissible text-center" role="alert">' +
				'<span class="spinner-border spin-x" role="status" aria-hidden="true"></span> Cargando</div>');
		},
		success: function (data) {
			$("#div-cnt-sub-module").html("");
			$("#div-cnt-sub-module").html(data.results);
		},
		error: function (response) {
			$("#div-cnt-sub-module").html('<div class="alert alert-danger alert-dismissible text-center" role="alert"><i class="bi bi-exclamation-circle"></i>'+
				' Error interno, intenta más tarde.</div>');
		}
	});
};

$(document).on("submit", ".form-up-sub-module", function (event) {
	//var idmod = $("#txt-id-mod-sub").val();
	var parametros = $(this).serialize();
	$.ajax({
		type: "POST",
		url: base_url + "/upInfoModule",
		data: parametros,
		dataType: "json",
		beforeSend: function (objeto) {
            $("#btn-up-sub").attr("disabled", true);
			$("#btn-up-sub").html('<span class="spinner-border spin-x" role="status" aria-hidden="true"></span> Actualizando');
		},
		success: function (datos) {
			if (datos.tipo == 'success') {
				$(".form-up-sub-module")[0].reset();
				$("#btn-close-mdl-up-sub").trigger("click");
				loadSubModules(reg);
				notifyMsg(datos.msg, '#', datos.tipo, '')
			}else{
                if(datos.errors){
                    jQuery.each(datos.errors, function(key, value){
						notifyMsg(value, '#', 'danger', 'mdl-up-info-sub');
                    });
                }else{
					notifyMsg(datos.msg, '#', datos.tipo, 'mdl-up-info-sub');
                }
			}
			$("#btn-up-sub").html('<i class="bi bi-check-circle"></i> Actualizar');
			$("#btn-up-sub").attr("disabled", false);
		},
		error: function (data) {
			$("#btn-up-sub").html('<i class="bi bi-check-circle"></i> Actualizar');
			$("#btn-up-sub").attr("disabled", false);
			notifyMsg('Error interno, intenta más tarde', '#', 'danger', 'mdl-up-info-sub')
		}
	});
	event.preventDefault();
});

$(document).on("click", ".mdl-del-reg", function() {
	$("#txt-list-dels").val($(this).data("id"));
    $("#txt-mod-id").val($(this).data("modid"));
    $("#p-msg-del").text('Registro seleccionado: '+$(this).data("nom"));
});

$("#form-up-edo").submit(function (event) {
	var parametros = $(this).serialize();
    var mode = $("#txt-mod-id").val();
	$.ajax({
		type: "POST",
		method: "POST",
		url: base_url + "/delModule",
		data: parametros,
		dataType: "JSON",
		beforeSend: function (objeto) {
            $("#btn-up-edo").attr("disabled", true);
			$("#btn-up-edo").html('<span class="spinner-border spin-x" role="status" aria-hidden="true"></span> Actualizando');
		},
		success: function (datos) {
			$("#btn-up-edo").html('<i class="bi bi-check-circle"></i> Aceptar');
			$("#btn-up-edo").attr("disabled", false);
			if (datos.tipo == "success") {
				$("#btn-close-mdl-up-edo").trigger("click");
				notifyMsg(datos.msg, '#', datos.tipo, '');
                $("#form-up-edo")[0].reset();
                if(mode==0){
                    load(1);
                }else{
                    loadSubModules(reg);
                }
			}else{
				$("#div-cnt-msg-up-edo").html('<div class="alert alert-'+datos.tipo+'" role="alert"><i class="'+datos.icon+'"></i>' +datos.msg+	'</div>');
				setTimeout(function () {
					$("#div-cnt-msg-up-edo").html('');
				}, 3000);
			}
		},
		error: function (data) {
			$("#form-up-edo")[0].reset();
			$("#btn-up-edo").html('<i class="bi bi-check-circle"></i> Aceptar');
			$("#btn-up-edo").attr("disabled", false);
			$("#div-cnt-msg-up-edo").html('<div class="alert alert-danger" role="alert"><i class="fas fa-exclamation-circle"></i> Error interno, intenta más tarde.</div>');
			setTimeout(function () {
				$("#div-cnt-msg-up-edo").html('');
			}, 3000);
		}
	});
	event.preventDefault();
});


