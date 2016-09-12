function firmarDocumento(url,password, fileId, transaccionId) {
	var customDialog = {
		message: "<i class='fa fa-pencil fa-4x'></i> ¿Esta seguro que desea firmar el documento?",
		title: "Firmar Documento",
		buttons: {
			close: {
				label: "Cancelar",
				className: "btn-custom-default"
			},
			success: {
				label: "Firmar",
				className: "btn-custom",
				callback: function() {
					var process = {
						size : "large",
						closeButton : false,
						title : "Procesando Solicitud",
						message : "<i class='fa fa-spinner fa-spin fa-4x'></i> El sistema se encuentra procesando su solicitud.",
						buttons : {}
					};
					var removeModal = function() {
						$(".bootbox").modal("hide").remove();
						$('body').removeClass('modal-open');
						$('.modal-backdrop').remove();
					}
					$.ajax({
						type: 'POST',
						data: {
							password: password.val(),
							fileId: fileId,
							transaccionId: transaccionId
						},
						dataType: "json",
						url: url,
						beforeSend: function(xhr) {
							password.val("");
							bootbox.dialog(process);
						}
					})
					.always(function(xhr) {
						removeModal();
					})
					.success(function(xhr) {
						bootbox.dialog({
							closeButton : true,
							title : "Solicitud Procesada",
							message : "<i class='fa fa-check-circle fa-4x'></i> Documento Firmado Exitosamente!",
							buttons : {
								success : {
									label: "Aceptar",
									className: "btn-custom",
									callback: function(){
										window.location.href = xhr.folder;
									}
								}									
							}
						});
					})
					.fail(function(xhr) {
						bootbox.dialog({
							closeButton : true,
							title : "<span class='danger'>Error al Procesar Solicitud</span>",
							message : "<i class='fa fa-exclamation-triangle fa-4x'></i> " + xhr.responseJSON,
							buttons : {
								success : {
									label: "Aceptar",
									className: "btn-custom",
								}									
							}
						});
					});
					
				}
			}
		}
	};
	bootbox.dialog(customDialog);
}
function normalizarPermisos(url,fileId, transaccionId) {
	console.log(transaccionId);
	$.ajax({
  		type: 'POST',
  		url: url,
		data: {
			fileId: fileId,
			transaccionId: transaccionId
		}
	})
	.always(function(xhr) {});
}
