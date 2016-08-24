function formatData (repo) 
{
      if (repo.loading) return repo.text;

      var markup = '<option value="' + repo.id + '">' + repo.value + '</option>';

      return markup;
}

function formatRepoSelection (repo) 
{
      return repo.value || repo.id;
}

$(document).ready(function() {

	$(".search-merchant").select2({
	  ajax: {
	    url: search_mechant_url,
	    dataType: 'json',
	    delay: 250,
	    data: function (params) {
	      return {
	        q: params.term, // search term
	        page: params.page
	      };
	    },
	    processResults: function (data, params) {
	      // parse the results into the format expected by Select2
	      // since we are using custom formatting functions we do not need to
	      // alter the remote JSON data, except to indicate that infinite
	      // scrolling can be used
	      params.page = params.page || 1;

	      return {
	        results: data,
	        /*
	        pagination: {
	          more: (params.page * 30) < data.length
	        }
	        */
	      };
	    },
	    cache: true
	  },
	  escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
	  minimumInputLength: 3,
	  templateResult: formatData,
	  templateSelection: formatRepoSelection
	});

});