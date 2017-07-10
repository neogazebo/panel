$(document).ready(function() {

	var merchant_timer;
    var merchant_timer_request;

	$("#com_name_search").autocomplete({
		source: function(request,response) {
			if (merchant_timer_request) { 
	            merchant_timer_request.abort();
	        }

	        clearTimeout(merchant_timer);

	        var ms = 300;
	        var $this = $(this);
			var $element = $(this.element);
			var $element_val = $element.val();
			var previous_request = $element.data("jqXHR");

	        if($element_val.length >= 2) {
	            merchant_timer = setTimeout(function() {
	                merchant_timer_request = $.ajax({
	                    type: 'GET',
	                    url: search_mechant_url,
						dataType: "json",
						data: {
							q: request.term
						},
						beforeSend: function() {
		                    $('#com_name_search').addClass('search-process');
		                },
		                complete: function(){
		                    $('#com_name_search').removeClass('search-process');
		                },
	                    success: function(data) {
	                        response($.map( data, function(item) {
								return {
									label: item.value,
									value: item.id
								}
							}));
	                    }
	                });
	            }, ms);
			}
		},
		select: function(event, ui) {
			event.preventDefault();
			$(this).val(ui.item.label);
			$('#com_name').val(ui.item.value);
			$('#com_id').val(ui.item.value);
			if(ui.item.value == 'Merchant Not Found!') {
				$('#com_name').val('');
			}
		},
		change: function(event, ui) {
			if($(this).val() == '') {
				$('#com_name').val('');
			}
		}
	});
	
	var email_timer;
    var email_search_request;

	$("#member_email_search").autocomplete({
		source: function(request,response) {
			if (email_search_request)
	            email_search_request.abort();

	        clearTimeout(email_timer);

	        var ms = 300;
	        var $this = $(this);
			var $element = $(this.element);
			var $element_val = $element.val();
			var previous_request = $element.data("jqXHR");

	        if($element_val.length >= 2) {
	            email_timer = setTimeout(function() {
	                email_search_request = $.ajax({
	                    type: 'GET',
	                    url: search_member_email_url,
						dataType: "json",
						data: {
							q: request.term
						},
						beforeSend: function() {
		                    $('#member_email_search').addClass('search-process');
		                },
		                complete: function() {
		                    $('#member_email_search').removeClass('search-process');
		                },
	                    success: function(data) {
	                        response($.map( data, function(item) {
								return {
									label: item.value,
									value: item.id
								}
							}));
	                    }
	                });
	            }, ms);
			}
		},
		select: function(event, ui) {
			event.preventDefault();
			$(this).val(ui.item.label);
		}
	});
});
