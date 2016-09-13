function formatData (repo) 
{
      if (repo.loading) return repo.text;

      var markup = '<option value="' + repo.id + '">' + repo.value + '</option>';

      return markup;
}

function formatRepoSelection (repo) 
{
      return repo.value || repo.text;
}

$(document).ready(function() {

	var $test = $(".search-merchant");

	// $(".search-merchant").select2({
	//   ajax: {
	//   	initSelection: function (element, callback) {
	//   		callback({ id: '', text: 'All' });
	// 	},
	//     url: search_mechant_url,
	//     dataType: 'json',
	//     delay: 250,
	//     data: function (params) {
	//       return {
	//         q: params.term, // search term
	//         page: params.page
	//       };
	//     },
	//     processResults: function (data, params) {
	//       // parse the results into the format expected by Select2
	//       // since we are using custom formatting functions we do not need to
	//       // alter the remote JSON data, except to indicate that infinite
	//       // scrolling can be used
	//       params.page = params.page || 1;

	//       return {
	//         results: data,
	//         /*
	//         pagination: {
	//           more: (params.page * 30) < data.length
	//         }
	//         */
	//       };
	//     },
	//     cache: true
	//   },
	//   escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
	//   minimumInputLength: 3,
	//   templateResult: formatData,
	//   templateSelection: formatRepoSelection
	// });
	
	var timer;
    var x;

	$("#com_name_search").autocomplete( {
		source: function(request,response) {

			if (x) 
	        { 
	            x.abort();
	        }

	        clearTimeout(timer);

	        var ms = 300;
	        var $this = $(this);
			var $element = $(this.element);
			var $element_val = $element.val();
			var previous_request = $element.data( "jqXHR" );

	        if($element_val.length >= 2) {
	            timer = setTimeout(function() {
	                x = $.ajax({
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

			/*
			var $this = $(this);
			var $element = $(this.element);
			var $element_val = $element.val();
			var previous_request = $element.data( "jqXHR" );
			
			if(previous_request) 
			{
				previous_request.abort();
				$('#com_name_search').removeClass('search-process');
			}

			if($element_val.length >= 3)
			{
				$element.data( "jqXHR", $.ajax( {
					type: "GET",
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
					success: function( data ){
						response($.map( data, function(item) {
							return {
								label: item.value,
								value: item.id
							}
						}));
					}
				}));
			}
			else
			{
				$('#com_name_search').removeClass('search-process');
			}
			*/
		},
		select: function(event, ui) {
			event.preventDefault();
			$(this).val(ui.item.label);
			$('#com_name').val(ui.item.value);

			if(ui.item.value == 'Merchant Not Found!')
			{
				$('#com_name').val('');
			}
		},
		change: function(event, ui) {
			if($(this).val() == '')
			{
				$('#com_name').val('');
			}
		}
	});

});