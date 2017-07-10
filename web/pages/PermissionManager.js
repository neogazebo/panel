$(document).ready(function() {

	$('body').on('change', '.module-selector', function(e) {
		e.preventDefault();
		var module = $(this).val();
		
		if(module)
		{
			$.ajax({
		        type: 'POST',
		        dataType: 'json',
		        url: '/rbac/permission/get-module-components',
		        data: {
		        	'module': module
		        },
		        beforeSend: function() {
		            $('.box-body').waitMe({
		                effect : 'stretch',
		                text : 'Loading resources...',
		                bg : 'rgba(255,255,255,0.7)',
		                color : '#000',
		                sizeW : '',
		                sizeH : ''
		            });
		        },
		        complete: function(){
		            $('.box-body').waitMe('hide');
		        },
		        success: function(data) {
		        	$('.resource-wrapper').html(data.data);
		        }
		    });
		}
		else
		{
			$('.resource-wrapper').empty();
		}
	});
});