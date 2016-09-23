$('div.modal').on('shown.bs.modal', function(){
	var form_id = $(this).find('form').attr('id');
	$('.close').on('click',function(){
		document.getElementById(form_id).reset();
	});
	$('body #'+form_id).on('beforeSubmit', function(event) {
		// stop multiple submit using this
		event.stopImmediatePropagation();
		event.preventDefault();
	    var form = $(this);
	    if(form.find('.has-error').length) {
	        return false;
	    }
	    $.ajax({
	        url: form.attr('action'),
	        type: 'post',
	        data: form.serialize(),
	        beforeSend: function() {
	            $('.modal-dialog').waitMe({
	                effect : 'stretch',
	                text : 'Saving...',
	                bg : 'rgba(255,255,255,0.7)',
	                color : '#000',
	                sizeW : '',
	                sizeH : ''
	            });
	        },
	        complete: function() {
	            $('.modal-dialog').waitMe('hide');
	        },
	        success: function(data) {
	        	if(!data.error) {
	                $('.modal').modal('hide');
	                swal({
	                    title: 'Success',   
	                    timer: 1000,
	                    text: 'Data is successfully saved',
	                    type: "success",
	                    showConfirmButton: false
	                },
	                function() {   
	                    window.location.reload();
	                });
	            } else {
	                var msg = [];
	                	item_msg = Object.values(data.message)[0];
	                for (var i = item_msg.length - 1; i >= 0; i--) {
	                	msg = item_msg[i];
	                }
	                if(data.error == 1000) {
	                    swal({
	                        title: 'System Error',   
	                        html: true,
	                        text: msg,
	                        type: "error",
	                    });
	                }
	            }
	        }
	    });

	    return false;
	});
});
function formReset() {
	$('.modal').modal('hide');
}