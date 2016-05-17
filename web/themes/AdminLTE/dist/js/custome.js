$(function() {
	// this function to hide flash message in 3 second
	function timeout() {
		setTimeout(function() { 
			$(".alert-dismissable").hide(); 
		}, 3000);
	};
	timeout();

	var customAlert = function(status,message) {
		var html = "<div class='row'><div class='col-xs-12 col-sm-12 col-lg-12'>";
		if(status == 'success') {
			html += "<div class='alert alert-dismissable alert-success'>";
			html += message;
			html += "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>";
			html += "</div>";
		} else if(status == 'error') {
			html += "<div class='alert alert-dismissable alert-danger'>";
			html += message;
			html += "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>";
			html += "</div>";
		}
		html += "</div></div>";
		$('.content-wrapper').prepend(html);
		timeout();
	}

	// this function to show render modal 
	$('.modalButton').on('click',function() {
		$('#modal').modal('show')
			.find('#modalContent')
			.load($(this).attr('value'));
	});

	// this function to generate role auto maticaly
	$('.ajaxRequest').on('click',function() {
		var Url = $(this).attr('value');
			reload = $(this).data('key');
			dom = $('body');
		swal({
			  title: "Synchronize role",
			  text: "",
			  type: "info",
			  showCancelButton: true,
			  closeOnCancel: true,
			  closeOnConfirm: false,
			  showLoaderOnConfirm: true,
		}, function() {
			$.get(Url,function(results) {
				// data = JSON.parse(results);
				// if (data.status = 'success') {
				// 	dom.load(reload);
				// 	swal("Syncronized", "Successed", "success");
				// }else{
				// 	swal("Syncronized","Failed!", "error");
				// }
			})
		});
	});

	$('.deleteBtn').on('click',function() {
		var Url = $(this).attr('value');
			name = Url.match(/\w+$/)[0];
			element = $(this).closest('tr');
		swal({
			title: "Delete " + name + " role",
			text: "Are you sure want to delete this?",
			type: "info",
			showCancelButton: true,
			closeOnCancel: true,
			closeOnConfirm: false,
			showLoaderOnConfirm: true,
		}, function() {
			$.get(Url, function(results) {
				// data = JSON.parse(results);
				// if (data.status = 'success') {
					// element.remove();
					// location.reload();
					// swal.close();
					// message = "Delete " + name + " role successed!";
					// customAlert(data.status,message);
					// element.remove();
				// } else {
					// swal.close();
					// element.remove();
					// message = "Delete " + name + " role failed!";
					// customAlert('error', message);
				// }
			})
		});
	});


    $('#search').multiselect({
        search: {
            left: '<input type="text" name="q" class="form-control" placeholder="Search..." />',
            right: '<input type="text" name="q" class="form-control" placeholder="Search..." />',
        }
    });

    $('#addChild').on('submit', function(event) {
		// var permission = $('#search_to').val();
		thisRole = $(this).data('key');
    	swal({
			title: "Update Permission "+thisRole,
			text: "Are you sure want to do this?",
			type: "info",
			showCancelButton: true,
			closeOnCancel: true,
			closeOnConfirm: false,
			showLoaderOnConfirm: true,
		}, function() {
	    	$.ajax({
    			type: 'POST',
    			url: $('#addChild').attr('action'),
    			data: $('#addChild').serialize(),
    			success: function(results) {
    	// 			data = JSON.parse(results);
					// if (data.status == 'success') {
					// 	$('body').load('detail?name='+thisRole);
					// 	swal.close();
					// 	timeout();
					// 	// message = "Add new Item successed!";
					// 	// customAlert(data.status,message);
					// }else{
					// 	swal.close();
					// 	timeout();
					// 	// message = "Add new Item failed!";
					// 	// customAlert(data.status,message);
					// }
    			}
    		});
		});
    	event.preventDefault();
    });
    	$('#search>option , #search_to>option').each(function(){
    		var id = $(this).text();
    		if (id.substr(id.length - 1) === '*') {
    			$(this).css({'font-weight':'bold'});
    		}
    	});

});