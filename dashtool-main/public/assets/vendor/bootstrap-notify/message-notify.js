function notifyMsg(mensaje, link, tipo, div = '') {
	$.notify({
		// options
		icon: (tipo==='success'? 'bi bi-check-circle' : (tipo==='danger'?'bi bi-x-circle': 'bi bi-info-circle') ),
		title: "<strong> </strong>",
		message: mensaje,
		url: link,
		target: '_blank'
	},{
		// settings
		element: (div==""?'body':'div#'+div),
		position: null,
		type: tipo,
		allow_dismiss: true,
		newest_on_top: false,
		showProgressbar: false,
		placement: {
			from: "top",
			align: "right"
		},
		offset: 20,
		spacing: 10,
		z_index: 1031,
		delay: 5000,
		timer: 1000,
		url_target: '_blank',
		mouse_over: 'pause',
		animate: {
			enter: 'animated fadeInDown',
			exit: 'animated fadeOutUp'
		},
		onShow: null,
		onShown: null,
		onClose: null,
		onClosed: null,
		icon_type: 'class',
        template: '<div data-notify="container" class="notify-bootstrap col-lg-3 col-md-4 col-4 pb-3 pt-3 alert alert-{0} alert-dismissible" role="alert">' +
			'<button type="button" class="btn-close p-2 mt-1" data-bs-dismiss="alert" aria-label="Close"></button>' +
			'<span data-notify="icon"></span> ' +
			'<span data-notify="title">{1}</span> ' +
			'<span class="me-1" data-notify="message">{2}</span>' +
			'<div class="progress" data-notify="progressbar">' +
				'<div class="progress-bar progress-bar-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>' +
			'</div>' + '<a href="{3}" target="{4}" data-notify="url"></a>' +
		'</div>'
	});
}
