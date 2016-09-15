/*
 * function merchant-hq
*/

function showError(message, error_field)
{
	for (var error in message) {
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
        complete: function() {
            $('.modal-dialog').waitMe('hide');
        },
        success: function(data) {
            if(!data.error) {
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
            } else {
                var msg = '';

                if(data.error == 9000) {
                    if(data.message.com_name)
                        showError(data.message.com_name, '.com-name-error');

                    if(data.message.com_subcategory_id)
                        showError(data.message.com_subcategory_id, '.com-category-error');
                }
                
                if(data.error == 1000) {
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

function op_confirm(com_speciality, children)
{
    console.log(children);
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: '/speciality/default/set-speciality',
        beforeSend: function() {
            $('.box').waitMe({
                effect : 'stretch',
                text : 'Saving...',
                bg : 'rgba(255,255,255,0.7)',
                color : '#000',
                sizeW : '',
                sizeH : ''
            });
        },
        complete: function(){
            $('.box').waitMe('hide');
        },
        data: {
            'com_speciality': com_speciality,
            'op_confirmation': true,
            'children': children
        },
        success: function(data) {
            if(!data.error) {
                swal({
                    title: 'Operation Status',   
                    html: true,
                    text: 'Data is successfully saved',
                    type: "success",
                },
                function() {   
                    window.location.reload();
                });
            }
        }
    });
}

function delete_confirm(com_speciality)
{
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: '/speciality/default/delete',
        beforeSend: function() {
            $('.box').waitMe({
                effect : 'stretch',
                text : 'Deleting...',
                bg : 'rgba(255,255,255,0.7)',
                color : '#000',
                sizeW : '',
                sizeH : ''
            });
        },
        complete: function(){
            $('.box').waitMe('hide');
        },
        data: {
            'com_speciality': com_speciality,
        },
        success: function(data) {
            if(!data.error) {
                swal({
                    title: 'Operation Status',   
                    html: true,
                    text: 'Data is successfully deleted',
                    type: "success",
                },
                function() {   
                    window.location.reload();
                });
            } else {
                swal({
                    title: 'System Error',   
                    html: true,
                    text: data.message,
                    type: "error",
                });
            }
        }
    });
}

$(document).ready(function() {
    $('#search_merchant').multiselect({
        search: {
            right: '<input type="text" name="q" class="form-control" placeholder="Search..." />',
        },
        //'keepRenderingSort': true,
        'sort': function(Multiselect, $options, event, silent, skipStack) {
            //console.log(Multiselect);
        }
    });

    $(".table").tablesorter({ 
        // pass the headers argument and assing a object 
        headers: { 
            // assign the secound column (we start counting zero) 
            3: { 
                // disable it by setting the property sorter to false 
                sorter: false 
            }, 
        } 
    }); 

	$('#add-hq-modal, .edit-hq-modal').on('show.bs.modal', function (e) {
		$('.com-name-error').html('');
        $('.com-category-error').html('');
		$('#com_name').val('');
        $('#com_subcategory_id').val('');
	})

	$('.manage-hq').submit(function(e) {
        e.preventDefault();

        var op = $(this).data('op'),
            op_url = '/system/merchant-hq/op';

        switch(op) {
            case 'add':
                var modal_instance = '#add-hq-modal';
                break;

            case 'edit':
                var modal_instance = '.edit-hq-modal';
                break;
        }
        
        var form_data = new FormData(this);

        form_data.append('op', op);

        if(op == 'edit') {
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
                    save(op_url, form_data, modal_instance);
            });
        } else {
            save(op_url, form_data, modal_instance);
        }
    });
    
    var timer;
    var x;

    $('#search-merchant').keyup(function() {

        if (x) 
        { 
            x.abort();
        }

        clearTimeout(timer);

        var ms = 300,
            keyword = $(this).val(),
            com_speciality = $('.com_speciality').val();

        if(keyword.length >= 2) {
            timer = setTimeout(function() {
                x = $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: '/speciality/default/search',
                    data: {
                        'keyword': keyword,
                        'com_speciality': com_speciality
                    },
                    beforeSend: function() {
                        $('#search-merchant').addClass('search-process');
                    },
                    complete: function(){
                        $('#search-merchant').removeClass('search-process');
                    },
                    success: function(data) {
                        if(!data.error) {
                            $("#search_merchant").html(data.data);
                            //$("#search_merchant").multiselect('destroy');
                            //$("#search_merchant").multiselect();
                        } else {
                            swal({
                                title: 'System Error',   
                                html: true,
                                text: data.message,
                                type: "error",
                            });
                        }
                    }
                });
            }, ms);
        }
    });

    $('#add-merchant-child').submit(function(e) {
        e.preventDefault();
        var op_url = '/speciality/default/set-speciality',
            form_data = new FormData(this);
            children = JSON.stringify($('#search_merchant_to').val());
            com_speciality = $('.com_speciality').val();

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: op_url,
            data: {
                children : children,
                com_speciality : com_speciality
            },
            beforeSend: function() {
                $('.box').waitMe({
                    effect : 'stretch',
                    text : 'Analyzing operation...',
                    bg : 'rgba(255,255,255,0.7)',
                    color : '#000',
                    sizeW : '',
                    sizeH : ''
                });
            },
            complete: function(){
                $('.box').waitMe('hide');
            },
            success: function(data) {
                if(!data.error) {
                    swal({
                        title: 'Success',   
                        html: true,
                        text: 'Data is successfully saved',
                        type: "success",
                    },
                    function() {   
                        window.location.reload();
                    });
                } else {
                    if(data.error == 1000) {
                        swal({
                            title: 'Operation Status',   
                            html: true,
                            text: data.message,
                            type: "warning",
                        });
                    }

                    if(data.error == 2000 || data.error == 3000) {
                        swal({
                            title: "Are you sure?",   
                            text: data.message,
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
                            if(isConfirm) {
                                op_confirm(com_speciality, children);
                            } else {
                                window.location.reload();
                            }
                        });
                    }
                }
            }
        });
    });

    $('.delete').click(function(e) {

        e.preventDefault();

        var com_speciality = $(this).data('id');

        swal({
            title: "Are you sure?",   
            text: 'You are about to do an irreversable operation',
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
            if(isConfirm) {
                delete_confirm(com_speciality);
            }
        });

    });
});
