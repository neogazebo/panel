$(function(){

	// this function to hide flash message in 3 second
	function timeout(){
		setTimeout(function(){ 
			$(".alert-dismissable").hide(); 
		}, 3000);
	};
	timeout();

	function cutomeAlert(status,message){
		var html  = "<div class='row'><div class='col-xs-12 col-sm-12 col-lg-12'>";
			if(status == 'success'){
				html += "<div class='alert alert-dismissable alert-success'>";
				html += message;
				html += "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>";
				html += "</div>";
			}else if(status == 'error'){
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
	$('.modalButton').on('click',function(){
		$('#modal').modal('show')
		.find('#modalContent')
		.load($(this).attr('value'));
	});

	// this function to generate role auto maticaly
	$('.ajaxRequest').on('click',function(){
		var Url = $(this).attr('value');
			reload = $(this).data('key');
			dom = $('body');
		swal({
			  title: "Syncronize Role",
			  text: "",
			  type: "info",
			  showCancelButton: true,
			  closeOnCancel: true,
			  closeOnConfirm: false,
			  showLoaderOnConfirm: true,
		},function(){
			$.get(Url,function(results){
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
			  title: "Delete "+name+" Role",
			  text: "Are You Sure ?",
			  type: "info",
			  showCancelButton: true,
			  closeOnCancel: true,
			  closeOnConfirm: false,
			  showLoaderOnConfirm: true,
		}, function() {
			$.get(Url,function(results) {
				data = JSON.parse(results);
				if (data.status = 'success') {
					swal.close();
					message = "Delete "+name+" Role successed!";
					cutomeAlert(data.status,message);
					element.remove();
				} else {
					swal.close();
					message = "Delete "+name+" Role failed!";
					cutomeAlert('error',message);
				}
			})
		});
	});


    $('#search').multiselect({
        search: {
            left: '<input type="text" name="q" class="form-control" placeholder="Search..." />',
            right: '<input type="text" name="q" class="form-control" placeholder="Search..." />',
        }
    });

    $('#addChild').on('submit',function(event) {
	// var permission = $('#search_to').val();
		thisRole = $(this).data('key');
    	swal({
			  title: "Add child Role",
			  text: "Are You Sure ?",
			  type: "info",
			  showCancelButton: true,
			  closeOnCancel: true,
			  closeOnConfirm: false,
			  showLoaderOnConfirm: true,
		}, function() {
	    	$.ajax({
    			type : 'POST',
    			url : $('#addChild').attr('action'),
    			data: $('#addChild').serialize(),
    			success: function(results) {
    	// 			data = JSON.parse(results);
					// if (data.status == 'success') {
					// 	$('body').load('detail?name='+thisRole);
					// 	swal.close();
					// 	timeout();
					// 	// message = "Add new Item successed!";
					// 	// cutomeAlert(data.status,message);
					// }else{
					// 	swal.close();
					// 	timeout();
					// 	// message = "Add new Item failed!";
					// 	// cutomeAlert(data.status,message);
					// }
    			}
    		});
		});
    	event.preventDefault();
    });

});