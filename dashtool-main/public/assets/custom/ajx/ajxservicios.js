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
		url: base_url + '/loadServicios',
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
		url: base_url + "/delCliente",
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

function loadInfoServicio  (reg) {
	$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
	$.ajax({
		url: 		base_url + "/loadInfoServicio",
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

function loadInfoJunta  (reg) {
	$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
	$.ajax({
		url: 		base_url + "/loadInfoJunta",
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
		url: base_url + "/upInfoRegServicio",
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
				loadInfoServicio(reg);
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

$(document).on("submit", ".form-up-junta", function (e) {
	var parametros = $(this).serialize();
	$.ajax({
		type: "POST",
		url: base_url + "/upInfoRegJunta",
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
				loadInfoJunta(reg);
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

function loadJuntas(reg) {
	$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
	$.ajax({
		url: 		base_url + "/loadJuntas",
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
$(document).on("click", ".btn-add-module-id", function() {
	$('#module_id').val($(this).attr("data-id"));
});
$(document).on("submit", ".form-add-reg", function (e) {
	var parametros = $(this).serialize();
	$.ajax({
		type: 		"POST",
		dataType: 	"JSON",
		method: 	"POST",
		url: 		base_url + "/storeServicio",
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
$(document).on("change", ".upProfileImg", function (e) {
    var fl = document.getElementById('fileimages');
    var ln = fl.files.length;
    var formData 	= new FormData();

    if (ln <= 0) {
        notifyMsg('Seleccione al menos un archivo.', '#', 'danger', '');
        return;
    } else {
        for (var i = 0; i<ln; i++) {
            formData.append('file', $('#fileimages')[0].files[i]);
			formData.append('id', $('#id').val());
            $.ajax({
                url: base_url + "/upFileJunta",
                data: formData,
                type: 'POST',
                contentType: false,
                processData: false,
                xhr: function () {
                    var xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener("progress", function (evt) {
                        if (evt.lengthComputable) {
                            var percentComplete = evt.loaded / evt.total;
                            percentComplete = parseInt(percentComplete * 100);
                            $('#progUpAnyImg').text(percentComplete + '%');
                            $('#progUpAnyImg').css('width', percentComplete + '%');
                        }
                    }, false);
                    return xhr;
                },
                beforeSend: function (objeto) {
                    $('#progUpAnyImg').removeAttr("class").attr("class", "bg-success text-center");
                    $('#progUpAnyImg').css('width', '0');
                    $('#btnUploadImgAny').attr("disabled", true);
                    $('#btnUploadImgAny').html('<span class="spinner-border spin-x" role="status" aria-hidden="true"></span>');
                },
                success: function (data) {
                    $('#progUpAnyImg').css('width', 100 + '%');
                    $('#progUpAnyImg').text('0%');
                    if (data.tipo == 'success') {
                        setTimeout(function () {
                            $('#progUpAnyImg').removeAttr("class").attr("class", "bg-default text-center");
                        }, 2000);
                        loadFileJunta($('#id').val());
                    } else {
                        $('#progUpAnyImg').removeAttr("class").attr("class", "bg-danger text-center");
                        setTimeout(function () {
                            $('#progUpAnyImg').removeAttr("class").attr("class", "bg-default text-center");
                        }, 2000);
                    }
                    $('#btnUploadImgAny').attr("disabled", false);
                    $('#btnUploadImgAny').html('<i class="bi bi-cloud-upload"></i> Subir');
                    notifyMsg(data.msg, '#', data.tipo, '');
                },
                error: function (data) {
                    $('#btnUploadImgAny').attr("disabled", false);
                    $('#progUpAnyImg').css('width', 100 + '%');
                    $('#progUpAnyImg').text('0%');
                    $('#progUpAnyImg').removeAttr("class").attr("class", "bg-danger text-center");
                    $('#btnUploadImgAny').html('<i class="bi bi-cloud-upload"></i> Subir');
                    setTimeout(function () {
                        $('#progUpAnyImg').removeAttr("class").attr("class", "bg-default text-center");
                        $('#progUpAnyImg').text('0%');
                    }, 2000);
                    notifyMsg('Error interno, intenta más tarde.', '#', 'danger', '');
                }
            });
        }
    }
    e.preventDefault();
});
function loadFileJunta(reg) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: base_url + "/loadFileJunta",
        method: "POST",
        dataType: "JSON",
        type: "POST",
        data: {
            reg: reg,
        },
        beforeSend: function (objeto) {
            $("#div-cnt-reg").html('<div class="alert alert-dark text-center" role="alert">' +
                '<span class="spinner-border spin-x" role="status" aria-hidden="true"></span> Cargando</div>');
        },
        success: function (data) {
            $("#div-cnt-reg").html(data.results);
        },
        error: function (response) {
            $("#div-cnt-reg").html('<div class="alert alert-danger text-center" role="alert"><i class="bi bi-x-circle"></i> Error interno, intenta más tarde.</div>');
        }
    });
};
$(document).on("submit", ".form-add-junta", function (e) {
	var parametros = $(this).serialize();
	$.ajax({
		type: 		"POST",
		dataType: 	"JSON",
		method: 	"POST",
		url: 		base_url + "/storeJunta",
		data: 		new FormData(this),
		contentType: 	false,
		cache: 			false,
		processData: 	false,
		beforeSend: function(objeto) {
            $('#btn-add-junta').attr("disabled", true);
			$("#btn-add-junta").html('<span class="spinner-border spin-x" role="status" aria-hidden="true"></span> Agregando');
		},
		success: function(datos) {
			$("#btn-add-junta").html('<i class="bi bi-check-circle"></i> Continuar');
			$('#btn-add-junta').attr("disabled", false);
			if (datos.tipo == "success") {
				$('#btn-add-junta').attr("disabled", true);
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
			$("#btn-add-junta").html('<i class="bi bi-check-circle"></i> Continuar');
			$("#btn-add-junta").attr("disabled", false);
			notifyMsg('Error interno, intenta más tarde.', '#', 'danger', '');
		}
	});
	e.preventDefault();
});



















































