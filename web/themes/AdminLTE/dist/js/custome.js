$(function(){

	setTimeout(function(){ 
		$(".alert-dismissable").hide(); 
	}, 3000);

	$('#modalButton').on('click',function(){
		$('#modal').modal('show')
		.find('#modalContent')
		.load($(this).attr('value'));
	});

	$("#modalHide").on('click',function(){
		alert('oke');
		$('#modal').modal('hide');
	});

});