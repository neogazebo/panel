/*
* Js functions for image cropper widget
* Author : tajhul <tajhul@ebizu.com>
*/

var target_image = null;
var target_field = null;
var jcrop_api = null;

$(function(){
    //console.log(baseURL);
    $('body').on('click','.eb-cropper', function(e){
        e.preventDefault();
        target_image = $(this).data('image');
        target_field = $(this).data('field');
    });
});

// on close modal
$('body').on('hidden.bs.modal', function (e) {
    $('#form-clearance').submit();
    $('#w').val('');
    $('#table-crop').hide();
    // clearFileInput
    clearFileInput();
});

// on show modal
$('body').on('show.bs.modal', function (e) {

    // destroy jcrop
    destroyCrop();

    $('#block-src').find('.image-to-crop').hide();
    $('#block-dst').find('.image-result-crop').hide();

    $('#save-button').hide();
    $('#crop-image').hide();
    $('#skip-crop').hide();

});

$(document).on('change', '#photoimg', function(){
    $('#imageform').submit();
});

$(document).ready(function () {
    
    $('.image-to-crop').hide();
    $('.image-result-crop').hide();
    $('#save-button').hide();
    $('#crop-image').hide();
    $('#skip-crop').hide();

    //form upload
    $("#imageform").on('submit', (function (e) {
        e.preventDefault();
        var $form = $(this);
        $.ajax({
            url: $form.attr('action'), // Url to which the request is send
            type: "POST",             // Type of request to be send, called as method
            data: new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
            contentType: false,       // The content type used when sending data to the server.
            cache: false,             // To unable request pages to be cached
            processData: false,        // To send DOMDocument or non processed data file it is set to false
            beforeSend : function(){
                // destroy jcrop
                destroyCrop();
                $('#table-crop').show();
                $('#block-src').find('.image-to-crop').hide();
                $('#block-src').find('.image-loader').show();
                $('#block-src').find('.image-to-crop').attr('src','');
                
            },
            success: function (response)   // A function to be called if request succeeds
            {
                var data = JSON.parse(response);
                if(data.success == 1){
                    
                    $('#table-crop').show();
                 
                    $('#block-src').find('.image-to-crop').attr('src',data.image_url);
                    $('#block-src').find('.image-to-crop').css({
                        'height' : data.height +'px',
                        'width' : data.width +'px',
                        'max-height' : data.height +'px',
                        'max-width' : data.width +'px',
                    });
                    
                    $('#src-file').val(data.src_file);
                    $('#x-factor').val(data.xFactor);
                    $('#block-src').find('.image-default').hide();
                    $('#block-src').find('.image-to-crop').show();
                    $('#crop-image').show();
                    $('#skip-crop').show();
                    initCrop();
                } else {
                    alert(data.message);
                    $('#table-crop').hide();
                }
            }
        }).done(function(){
            $('#block-src').find('.image-loader').hide();
        });
    }));

    $('#crop-image').on('click', function(e){
        e.preventDefault();
        $('#action').val('crop');
        $('#form-crop').submit();
    });
    $('#skip-crop').on('click', function(e){
        e.preventDefault();
        $('#action').val('skip');
        $('#form-crop').submit();
    });

    // crop form
    $('#form-crop').on('submit', (function (e) {
        e.preventDefault();
        var $form = $(this);
        var actionBtn = $form.find('#action').val();
        
        if(checkCoords() === false)
            return false;

        //var xFactor = $('#x-factor').val();
        // jika ingin menggunakan X-factor dalam perhitungan, uncomment ini
        //var x         = $('#x').val()*xFactor;
        //var y         = $('#y').val()*xFactor;
        //var w         = $('#w').val()*xFactor;
        //var h         = $('#h').val()*xFactor;
        //
        //var name = $('#src-file').val();
        //var wsmall = $('#wsmall').val();
        //var hsmall = $('#hsmall').val();
        //var wbig = $('#wbig').val();
        //var hbig = $('#hbig').val();

        $.ajax({
            url: $form.attr('action'), // Url to which the request is send
            type: "POST",             // Type of request to be send, called as method
            data: $form.serialize(), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
            // jika ingin menggunakan X-factor dalam perhitungan, uncomment data dibawah ini & comment data diatas
            //data: {action:'crop',name:name,wsmall:wsmall,hsmall:hsmall,wbig:wbig,hbig:hbig,x:x,y:y,w:w,h:h,xFactor:xFactor},
            beforeSend : function(){
                $('#block-dst').find('.image-result-crop').hide();
                $('#block-dst').find('.image-loader').show();
                $('#block-src').find('.image-result-crop').attr('src','');
            },
            //dataType: 'json',
            success: function (response)   // A function to be called if request succeeds
            {
                var data = JSON.parse(response);

                if(data.success == 1){
                    $('#block-dst').find('.image-default').hide();
                    $('#block-dst').find('.image-result-crop').show();
                    $('#block-dst').find('.image-result-crop').attr('src',data.image_url);
                    $('#final-file').val(data.src_file);
                    $('#save-button').show();
                } else {
                    alert(data.message);
                }

            }
        }).done(function(){
            $('#block-dst').find('.image-loader').hide();
        });
    }));

    // publish
    $('#save-button').on('click', function(e){
        e.preventDefault();
        $('#form-publish-crop').submit();
    });
    $('#form-publish-crop').on('submit', (function (e) {
        e.preventDefault();
        var $form = $(this);
        $.ajax({
            url: $form.attr('action'), // Url to which the request is send
            type: "POST",             // Type of request to be send, called as method
            data: $form.serialize(), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
            beforeSend : function(){
                $('.crop-loader').show();
            },
            success: function (response)   // A function to be called if request succeeds
            {
                var data = JSON.parse(response);
                if(data.success == 1){
                    window.parent.$('#'+target_image).attr('src', data.image_url);
                    window.parent.$('#'+target_field).val(data.src_file);

                    window.parent.$('#cropper-modal').modal('hide');
                } else {
                    alert(data.message);
                }
            }
        }).done(function(){
            $('.crop-loader').hide();
        });
    }));

    // form clearance residual files
    $('#form-clearance').on('submit', (function (e) {
        e.preventDefault();
        var $form = $(this);
        $.ajax({
            url: $form.attr('action'), // Url to which the request is send
            type: "POST",             // Type of request to be send, called as method
            data: {name:$('#src-file').val()},
            beforeSend : function(){
            },
            success: function (response)   // A function to be called if request succeeds
            {
            }
        }).done(function(){});
    }));

});

function initCrop(){
    $('.image-to-crop').Jcrop({
        aspectRatio: 1.5,
        onChange: updateCoords,
        onSelect: updateCoords,
    },function(){
        jcrop_api = this;
        $('.jcrop-holdermain').parent().find('div:first').next().hide();
    });
}

function destroyCrop(){
    if(jcrop_api !== null)
        jcrop_api.destroy();
}


function updateCoords(c)
{
    $('#x').val(c.x);
    $('#y').val(c.y);
    $('#w').val(c.w);
    $('#h').val(c.h);
    
    // hide bug key symbol css
    setTimeout(function(){
       $('.jcrop-keymgr').hide();
    },200);      
}

function checkCoords()
{
    var action = $('#action').val();
    if(action !== '' && action !== 'skip'){
        if (parseInt($('#w').val())) return true;
        alert('Please select a crop region then press submit.');
        return false;
    } else {
        return true;
    }
}

function clearFileInput(){
    var input = $('#photoimg');
    input.replaceWith(input.val('').clone(true));
}