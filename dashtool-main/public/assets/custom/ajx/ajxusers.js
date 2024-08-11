var limite 		= 10;
var filter 		= 0;
var order 		= "desc";
var order_by 	= "id";
var search 		= "";
var url 		= "";
var user 		= 0;
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
		url: base_url + '/loadUsers',
		method: 'POST',
		dataType: 'JSON',
		data: {
			page: 	page,
			search: search,
			filter: filter,
			limite: limite,
			url: 	url,
			order: 	order,
			order_by: order_by,
			act_fc: ($('#chk-act-fc').is(':checked')?1:0),
			dt_ini: $('#dt-ini').val(),
			dt_fin: $('#dt-fin').val(),
			user: 	user,
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

$(document).on("click", ".chk-select-delete", function() {
    var table = document.getElementById('table-users');
    allCheckboxes = table.querySelectorAll('tbody [type="checkbox"]');
    count = 0;
    checkedState = false;
    list = [];
    // Count checked boxes
    allCheckboxes.forEach(c => {
        if (c.checked) {
            checkedState = true;
            count++;
            list.push($(c).data('id'));
        }
    });
    // Toggle toolbars
    if (checkedState) {
        selectedCount.innerHTML = count;
        toolbarBase.classList.add('d-none');
        toolbarSelected.classList.remove('d-none');
    } else {
        toolbarBase.classList.remove('d-none');
        toolbarSelected.classList.add('d-none');
    }
});

$(document).on("click", ".chk-delete-all", function() {
    var table = document.getElementById('table-users');
    allCheckboxes = table.querySelectorAll('tbody [type="checkbox"]');
    count = 0;
    checkedState = false;
    list = [];
    if ($(this).prop("checked")) {
        allCheckboxes.forEach(c => {
            $(c).prop('checked', true);
        });
        checked = true;
    }else{
        allCheckboxes.forEach(c => {
            $(c).prop('checked', false);
        });
        checked = false;
    }

    allCheckboxes.forEach(c => {
        if (c.checked) {
            checkedState = checked;
            list.push($(c).data('id'));
            count++;
        }
    });

    // Toggle toolbars
    if (checkedState) {
        selectedCount.innerHTML = count;
        toolbarBase.classList.add('d-none');
        toolbarSelected.classList.remove('d-none');
    } else {
        toolbarBase.classList.remove('d-none');
        toolbarSelected.classList.add('d-none');
    }
});

$(document).on("click", ".mdl-list-del", function() {
	$("#txt-list-dels").val(list);
    $("#p-msg-del").text('Selecciona la acción para  '+(count==1?'':'los')+' '+count+' '+(count==1?'registro seleccionado':'registros seleccionados'));
});

$(document).on("click", ".mdl-del-reg", function() {
	$("#txt-list-dels").val($(this).data("id"));
    $("#p-msg-del").text('Registro seleccionado: '+$(this).data("nom"));
});

$("#form-up-edo").submit(function (event) {
	var parametros = $(this).serialize();
	$.ajax({
		type: "POST",
		method: "POST",
		url: base_url + "/delUser",
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
                load(1);
			}else{
				$("#div-cnt-msg-up-edo").html('<div class="alert alert-'+datos.tipo+'" role="alert"><i class="'+datos.icon+'"></i>' +
				datos.msg+	'</div>');
				setTimeout(function () {
					$("#div-cnt-msg-up-edo").html('');
				}, 3000);
			}
		},
		error: function (data) {
			$("#form-up-edo")[0].reset();
			$("#btn-up-edo").html('<i class="bi bi-check-circle"></i> Aceptar');
			$("#btn-up-edo").attr("disabled", false);
			$("#div-cnt-msg-up-edo").html('<div class="alert alert-danger" role="alert"><i class="fas fa-exclamation-circle"></i>' +
				' Error interno, intenta más tarde.</div>');
			setTimeout(function () {
				$("#div-cnt-msg-up-edo").html('');
			}, 3000);
		}
	});
	event.preventDefault();
});

function loadInfoUser(reg) {
	$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
	$.ajax({
		url: 		base_url + "/loadInfoUser",
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
		url: base_url + "/upInfoReg",
		data: parametros,
		dataType: "json",
		beforeSend: function (objeto) {
            $("#btn-up-user").attr("disabled", true);
			$("#btn-up-user").html('<span class="spinner-border spin-x" role="status" aria-hidden="true"></span> Actualizando');
		},
		success: function (datos) {
			$("#btn-up-user").html('<i class="bi bi-check-circle"></i> Actualizar');
			$("#btn-up-user").attr("disabled", false);
			if (datos.tipo == 'success') {
				loadInfoUser(reg);
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
			$("#btn-up-user").html('<i class="bi bi-check-circle"></i> Actualizar');
			$("#btn-up-user").attr("disabled", false);
			notifyMsg('Error interno, intenta más tarde', '#', 'danger', '');
		}
	});
	e.preventDefault();
});

$(document).on("submit", ".form-up-password", function (e) {
	var parametros = $(this).serialize();
	$.ajax({
		type: "POST",
		dataType: "JSON",
		url: base_url + "/upPasswordUser",
		data: parametros,
		beforeSend: function (objeto) {
            $('#btn-up-passwd').attr("disabled", true);
			$("#btn-up-passwd").html('<span class="spinner-border spin-x" role="status" aria-hidden="true"></span> Actualizando...');
		},
		success: function (datos) {
			$("#btn-up-passwd").html('<i class="bi bi-check-circle"></i> Actualizar');
			$('#btn-up-passwd').attr("disabled", false);
			$(".form-up-password")[0].reset();
            if(datos.errors){
                jQuery.each(datos.errors, function(key, value){
					notifyMsg(value, '#', 'danger', '');
                });
            }else{
				notifyMsg(datos.msg, '#', datos.tipo, '');
            }
		},
		error: function (data) {
			$("#btn-up-passwd").html('<i class="bi bi-check-circle"></i> Actualizar');
			$('#btn-up-passwd').attr("disabled", false);
			notifyMsg('Error interno, intenta más tarde.', '#', 'danger', '');
		}
	});
	e.preventDefault();
});

function loadPermitsUser(reg) {
	$.ajax({
		url: base_url + "/loadPermitsUser",
		method: "POST",
		dataType: "JSON",
		type: "POST",
		data: {
			reg: reg
		},
		beforeSend: function (objeto) {
            $("#div-cnt-permits").html('<div class="col-md-12"><div class="alert alert-dark alert-dismissible text-center" role="alert">' +
				'<span class="spinner-border spin-x" role="status" aria-hidden="true"></span> Cargando</div></div>');
		},
		success: function (data) {
			$("#div-cnt-permits").html("");
			$("#div-cnt-permits").html(data.results);
		},
		error: function (response) {
			$("#div-cnt-permits").html('<div class="col-md-12"><div class="alert alert-danger alert-dismissible text-center" role="alert"><i class="bi bi-exclamation-circle"></i>' +' Error interno, intenta más tarde.</div></div>');
		}
	})
};

$(document).on("click", ".add-permit", function (e) {
    var status 	= $(this).is(':checked') ? 1 : 0;
    var sub = $(this).data('sub');
    var moduleId = $(this).data('moduleid');
    var subModuleId = $(this).data('submoduleid');
    var userId = $(this).data('userid');
    var urlSubModule = $(this).data('urlsubmodule');
    $.ajax({
        type: "POST",
        dataType: "JSON",
        method: "POST",
        url: base_url + "/asignPermit",
        data: {
            status: status,
            moduleId: moduleId,
            subModuleId: subModuleId,
            userId: userId,
            urlSubModule: urlSubModule,
        },
        beforeSend: function (objeto) {
            $("#span-"+sub).html('<span class="spinner-border spin-x" role="status" aria-hidden="true"></span>');
            $('#permit-'+sub).attr("disabled", true);
        },
        success: function (datos) {
            if(datos.type=='danger'){
                if (status==0) {
                    $('#permit-' + sub).prop('checked', true);
                }else{
                    $('#permit-' + sub).prop('checked', false);
                }
            }
            notifyMsg(datos.msg, '#', datos.type, '');
            $('#permit-'+sub).attr("disabled", false);
            $("#span-"+sub).html('');
        },
        error: function (data) {
            $("#span-"+sub).html('');
            $('#permit-'+sub).attr("disabled", false);
            if (status==0) {
                $('#permit-' + sub).prop('checked', true);
            }else{
                $('#permit-' + sub).prop('checked', false);
            }
            notifyMsg(data.statusText, '#', 'danger', '');
        }
    });
    //e.preventDefault();
});

$(document).on("submit", ".form-add-reg", function (e) {
	var parametros = $(this).serialize();
	$.ajax({
		type: 		"POST",
		dataType: 	"JSON",
		method: 	"POST",
		url: 		base_url + "/storeUser",
		data: 		new FormData(this),
		contentType: 	false,
		cache: 			false,
		processData: 	false,
		beforeSend: function(objeto) {
            $('#btn-add-reg').attr("disabled", true);
			$("#btn-add-reg").html('<span class="spinner-border spin-x" role="status" aria-hidden="true"></span> Agregando');
		},
		success: function(datos) {
			$("#btn-add-reg").html('<i class="bi bi-check-circle"></i> Continuar');
			$('#btn-add-reg').attr("disabled", false);
			if (datos.tipo == "success") {
				$('#btn-add-reg').attr("disabled", true);
				setTimeout(function () {
					$(window).attr('location', datos.url);
				}, 2000);
			}
            if(datos.errors){
                jQuery.each(datos.errors, function(key, value){
					notifyMsg(value, '#', 'danger', '');
                });
            }else{
				notifyMsg(datos.msg, '#', datos.tipo, '');
            }
		},
		error: function(data) {
			$("#btn-add-reg").html('<i class="bi bi-check-circle"></i> Continuar');
			$("#btn-add-reg").attr("disabled", false);
			notifyMsg('Error interno, intenta más tarde.', '#', 'danger', '');
		}
	});
	e.preventDefault();
});



















































