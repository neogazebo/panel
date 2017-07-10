$(function(){

	$('.cropper-ori').on('click',function(){
		$('.original-image').click();
	});

	function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
	        reader.onload = function (e) {
	            $('#imageSelect').attr('src', e.target.result);
	        }
	        reader.readAsDataURL(input.files[0]);
	    }
	}
	$(".original-image").change(function(){
	    readURL(this);
	});

	function showPreview(coords){
		var rx = 100 / coords.w;
		var ry = 100 / coords.h;

		$('#preview img').css({
			width: Math.round(rx * 500) + 'px',
			height: Math.round(ry * 370) + 'px',
			marginLeft: '-' + Math.round(rx * coords.x) + 'px',
			marginTop: '-' + Math.round(ry * coords.y) + 'px'
		});
	}
	
	$('#imageSelect').Jcrop({
		onChange: showPreview,
		onSelect: showPreview,
		aspectRatio: 1
	});

});