$(function(){

	// this function to hide flash message in 3 second
	function timeout(){
		setTimeout(function(){ 
			$(".alert-dismissable").hide(); 
		}, 3000);
	};
	timeout();

	function alert(status,message){
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
				data = JSON.parse(results);
				if (data.status = 'success') {
					dom.load(reload);
					swal("Syncronized", "Successed", "success");
				}else{
					swal("Syncronized","Failed!", "error");
				}
			})
		});
	});

	$('.deleteBtn').on('click',function(){
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
		},function(){
			$.get(Url,function(results){
				data = JSON.parse(results);
				if (data.status = 'success') {
					// swal("Delete "+name+" Role successed!","success");
					swal.close();
					message = "Delete "+name+" Role successed!";
					alert(data.status,message);
					element.remove();
				}else{
					// swal("Delete "+name+" Role Failed!", "error");
					swal.close();
					message = "Delete "+name+" Role failed!";
					alert('error',message);
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

    $('#addChild').on('submit',function(event){
    	var data = $('search_to').val();
    	$.ajax({
    			type : 'POST',
    			url : $(this).attr('action'),
    			data: $(this).serialize(),
    			success: function(results){
    				console.log(results);
    			}
    		});
    	event.preventDefault();
    })

});