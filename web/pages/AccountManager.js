function search(url, op_type, member_id, daterange)
{
	if(op_type == 'filter')
	{
		if(!daterange)
		{
			return false;
		}
	}

	if(op_type == 'reset')
	{
		$('#the_daterange').val('');
	}

    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: url,
        data: {
        	'op_type': op_type,
        	'member_id': member_id,
        	'daterange': daterange
        },
        beforeSend: function() {
            $('#list_point_history-container').addClass('ajax-loading');
            $('#filter').addClass('ajax-loading');
            $('#reset').addClass('ajax-loading');
        },
        complete: function(){
            $('#list_point_history-container').removeClass('ajax-loading');
            $('#filter').removeClass('ajax-loading');
            $('#reset').removeClass('ajax-loading');
        },
        success: function(data) {

            $('.history-grid-wrapper').html(data.output);

            if(op_type == 'filter')
            {
            	$('.reset-history').removeClass('hide');
            }
            else
            {
            	$('.reset-history').addClass('hide');
            }
        }
    });
}

$(document).ready(function() {

	var member_id = $('.member_id').val();
	
	$('body').on('click', '#filter ul.pagination li a', function(e) {
		e.preventDefault();
		var op_type = 'filter';
		var daterange = $('#the_daterange').val();
		var url = $(this).attr('href');
		search(url, op_type, member_id, daterange);
	});

	$('body').on('click', '#reset ul.pagination li a', function(e) {
		e.preventDefault();
		var op_type = 'reset';
		var daterange = $('#the_daterange').val();
		var url = $(this).attr('href');
		search(url, op_type, member_id, daterange);
	});

	$('.filter-history').click(function() {
		var op_type = $(this).data('op');
		var daterange = $('#the_daterange').val();
		var url = '/account/default/get-member-filtered-history';
	    search(url, op_type, member_id, daterange);
	}); 

	$('.reset-history').click(function(){
		var op_type = $(this).data('op');
		var daterange = $('#the_daterange').val();
		var url = '/account/default/get-member-filtered-history';
	    search(url, op_type, member_id, daterange);
	}); 

});