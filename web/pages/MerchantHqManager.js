function showError(message, error_field)
{
	for (var error in message) 
	{
		$(error_field).html('<p style="color: #F00; "><em>' + message[error] + '</em></p>');
	}
}

function save(op_url, form_data, modal_instance)
{
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
                    text: 'Data is successfully saved',
                    type: "success",
                },
                function() {   
                    window.location.reload();
                });
            }
            else
            {
                var msg = '';

                if(data.error == 9000)
                {
                    if(data.message.com_name)
                    {
                        showError(data.message.com_name, '.com-name-error');
                    }

                    if(data.message.com_subcategory_id)
                    {
                        showError(data.message.com_subcategory_id, '.com-category-error');
                    }
                }
                
                if(data.error == 1000)
                {
                    swal({
                        title: 'System Error',   
                        html: true,
                        text: data.message,
                        type: "error",
                    });
                }
            }
        }
    });
}

$(document).ready(function() {

	$('#add-hq-modal, .edit-hq-modal').on('show.bs.modal', function (e) {
		$('.com-name-error').html('');
        $('.com-category-error').html('');
		$('#com_name').val('');
        $('#com_subcategory_id').val('');
	})

	$('.manage-hq').submit(function(e) {

        e.preventDefault();

        var op = $(this).data('op');
        var op_url = '/system/merchant-hq/op';

        switch(op)
        {
            case 'add':
                var modal_instance = '#add-hq-modal';
                break;

            case 'edit':
                var modal_instance = '.edit-hq-modal';
                break;
        }
        
        var form_data = new FormData(this);

        form_data.append('op', op);

        if(op == 'edit')
        {
            var com_name_edited = $(this).find('.com_name_temp').val();

            swal({
                title: "Are you sure?",   
                text: "You are about to update <strong>" + com_name_edited + "</strong>",
                html: true,   
                type: "warning",   
                showCancelButton: true,   
                confirmButtonColor: "#DD6B55",   
                confirmButtonText: "Yes",   
                cancelButtonText: "Cancel",   
                closeOnConfirm: true,   
                closeOnCancel: true 
            }, 
            function(isConfirm) {   
                if (isConfirm) 
                {     
                    save(op_url, form_data, modal_instance);
                } 
            });
        }
        else
        {
            save(op_url, form_data, modal_instance);
        }
    });

});