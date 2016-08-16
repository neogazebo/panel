function showError(message, error_field)
{
	for (var error in message) 
	{
		$(error_field).html('<p style="color: #F00; "><em>' + message[error] + '</em></p>');
	}
}

$(document).ready(function() {

	$('#add-hq-modal, #edit-hq-modal').on('show.bs.modal', function (e) {
		$('.com-name-error').html('');
        $('.com-category-error').html('');
		$('#com_name').val('');
        $('#com_subcategory_id').val('');
	})

	$('#manage-hq').submit(function(e) {

        e.preventDefault();

        var op = $(this).data('op');

        switch(op)
        {
            case 'add':
                var op_url = '/merchant-hq/save';
                var modal_instance = '#add-hq-modal';
                break;

            case 'edit':
                var op_url = '/merchant-hq/edit';
                var modal_instance = '#edit-hq-modal';
                break;
        }
        
        var form_data = new FormData(this);
        
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: op_url,
            data: form_data,
            processData: false,
            contentType: false,
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
            complete: function(){
                $('.modal-dialog').waitMe('hide');
            },
            success: function(data) {

            	if(!data.error)
            	{
            		$(modal_instance).modal('hide');

	                swal({
			            title: 'Success',   
				        html: true,
				        type: "success",
			        },
				    function() {   
				        window.location.reload;
				    });
            	}
            	else
            	{
            		var msg = '';

            		console.log(data.message);

            		if(data.message.com_name)
		            {
		            	showError(data.message.com_name, '.com-name-error');
		            }

                    if(data.message.com_subcategory_id)
                    {
                        showError(data.message.com_subcategory_id, '.com-category-error');
                    }
            	}
            }
        });
    });

});