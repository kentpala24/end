function loadImageUser() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: base_url + "/loadImageUser",
        method: "POST",
        dataType: "JSON",
        type: "POST",
        data: {
            reg: 23,
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

$(document).on("change", ".upProfileImg", function (e) {
    var fl = document.getElementById('fileimages');
    var ln = fl.files.length;
    var formData 	= new FormData();

    if (ln <= 0) {
        notifyMsg('Seleccione al menos una imagen.', '#', 'danger', '');
        return;
    } else {
        for (var i = 0; i<ln; i++) {
            formData.append('file', $('#fileimages')[0].files[i]);
            $.ajax({
                url: base_url + "/upImgUser",
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
                        loadImageUser();
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

$(document).on("submit", ".form-up-account", function (e) {
    $.ajax({
        type: "POST",
        dataType: "JSON",
        method: "POST",
        url: base_url + "/upProfile",
        data: $(this).serialize(),
        beforeSend: function (objeto) {
            $("#btn-up-post").html('<span class="spinner-border spin-x" role="status" aria-hidden="true"></span> Validando...');
            $('#btn-up-post').attr("disabled", true);
        },
        success: function (datos) {
            $("#btn-up-post").html('<i class="bi bi-check-circle"></i> Actualizar');
            $('#btn-up-post').attr("disabled", false);
            if (datos.tipo == "success") {
                $("#btn-up-post").html('<span class="spinner-border spin-x" role="status" aria-hidden="true"></span> Actualizando');
                $('#btn-up-post').attr("disabled", true);
                setTimeout(function () {
                    $(window).attr('location', datos.url);
                }, 2000);
            }
            if (datos.errors) {
                jQuery.each(datos.errors, function (key, value) {
                    notifyMsg(value, '#', 'danger', '');
                });
            } else {
                notifyMsg(datos.msg, '#', datos.type, '');
            }
        },
        error: function (data) {
            $("#btn-up-post").html('<i class="bi bi-check-circle"></i> Actualizar');
            $("#btn-up-post").attr("disabled", false);
            notifyMsg(data.statusText, '#', 'danger', '');
        }
    });
    e.preventDefault();
});


$(document).on("submit", ".form-up-password", function (e) {
    $.ajax({
        type: "POST",
        dataType: "JSON",
        method: "POST",
        url: base_url + "/upPassword",
        data: $(this).serialize(),
        beforeSend: function (objeto) {
            $("#btn-up-passwd").html('<span class="spinner-border spin-x" role="status" aria-hidden="true"></span> Validando...');
            $('#btn-up-passwd').attr("disabled", true);
        },
        success: function (datos) {
            if (datos.errors) {
                jQuery.each(datos.errors, function (key, value) {
                    notifyMsg(value, '#', 'danger', '');
                });
            } else {
                notifyMsg(datos.msg, '#', datos.type, '');
            }
            $(".form-up-password")[0].reset();
            $('#btn-up-passwd').attr("disabled", false);
            $("#btn-up-passwd").html('<i class="bi bi-check-circle"></i> Actualizar');
        },
        error: function (data) {
            $("#btn-up-passwd").html('<i class="bi bi-check-circle"></i> Actualizar');
            $("#btn-up-passwd").attr("disabled", false);
            notifyMsg(data.statusText, '#', 'danger', '');
        }
    });
    e.preventDefault();
});

function loadPermits(reg) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: base_url + "/loadPermits",
        method: "POST",
        dataType: "JSON",
        type: "POST",
        data: {
            reg: reg,
        },
        beforeSend: function (objeto) {
            $("#div-cnt-permits").html('<div class="col-md-12"><div class="alert alert-dark text-center" role="alert">' +
                '<span class="spinner-border spin-x" role="status" aria-hidden="true"></span> Cargando</div></div>');
        },
        success: function (data) {
            $("#div-cnt-permits").html(data.results);
        },
        error: function (response) {
            $("#div-cnt-permits").html('<div class="col-md-12"><div class="alert alert-danger text-center" role="alert"><i class="bi bi-x-circle"></i> Error interno, intenta más tarde.</div></div>');
        }
    });
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