$(function () {
    /**
     * Adding floor and unit, showing modal for add floor and unit
     */
    var tag_array = new Array();
    if(isUpdate == 1) {
        tag_array = hiddenTag.split(',');
    }
    $('#add_tag').on('click', function () {
        var tag_id = $(this).attr('data-tag'),
            category = $('#company-com_subcategory_id').val();
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: baseUrl + 'merchant-signup/default/taglist/',
            data: { tag_id: tag_id, category: category },
            success: function (res) {
                $('#tag').empty();
                $.each(res, function (k, v) {
                    $('#tag').append('<option value="' + v.tag_id + '">' + v.tag_name + '</option>');
                });
                $('#modal-tag').modal({ show: true });
            }
        });
    });

    /**
     * get the unit, base on floor when the dropdown floor is change on modal add floor 
     */
    // $('#floor').on('change', function () {
    //     var floor_id = $('#floor').val();
    //     var mall_id = $('#add_floor').attr('data-mall');

    //     $.ajax({
    //         type: 'POST',
    //         dataType: 'json',
    //         url: baseUrl + 'business/unitlist/',
    //         data: {mall_id: mall_id, floor_id: floor_id},
    //         success: function (res) {
    //             $('#unit').html('');
    //             $.each(res, function (k, v) {
    //                 $('#unit').append('<option value="' + v.fpu_id + '">' + v.fpu_name + '</option>');
    //             });

    //             $('#modal-tag').modal({show: true});
    //         }
    //     });
    // });

    /**
     * Save the selected unit to database, close the modal add floor, and render the unit that has been selected
     */
    var tagg = '',
        tagged = '';
    $('#save-tag').on('click', function () {
        var tag_id = $('#tag').val();
        tagg = $('#company-tag').val();
        tagged = $('#company-tagged').val();
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: baseUrl + 'merchant-signup/default/settag/',
            data: { tag_id: tag_id },
            success: function (res) {
                if (res.success == 1) {
                    // if($('#company-tag').val() === ',')
                    //     $('#company-tag').val('');
                    tagg += res.data + ',';
                    $('#company-tag').val(tagg);
                    tag_array.push(res.data);
                    $('#tagging-list').append('<div style="background: #ececec; border: 1px solid #ccc; margin: 3px 0; padding: 5px">' + res.value.toString() + ' <a href="javascript:;" data-id="' + res.data + '" class="pull-right remove-tag"><i class="fa fa-times"></i></a></div>');
                    $('#tag').empty();
                    $('#modal-tag').modal('hide');
                } else if (res.success == 0) {
                    $('#tag-select').addClass('has-error');
                    $('#tag-select .help-block').html(res.message);
                }
            }
        });
    });

    $(document).on('click', '.remove-tag', function() {
        var id = $(this).data('id');
        tag_array.splice(tag_array.indexOf(id), 1);
        // if($('#company-tag').val() !== ',')
            $('#company-tag').val(tag_array.toString() + ',');
        // else
        //     $('#company-tag').val('');
        $(this).parent().fadeOut('slow');
    });

    /**
     * open the modal to edit/delete unit on spesific floor
     */
    // $(document).on('click', '.open-list-unit', function (e) {
    //     var floor = $(this).attr('data-floor');
    //     $('#modal-unit').modal('show')
    //             .find('#grid-container')
    //             .load(baseUrl + 'business/merchantunitlist', {floor: floor});
    //     e.preventDefault();
    // });

    /**
     * load new data floor and unit when the modal edit/delete unit is closed
     */
    // $('#modal-unit').on('hide.bs.modal', function () {
    //     $.ajax({
    //         type: 'GET',
    //         dataType: 'json',
    //         url: baseUrl + 'business/loadmerchantfloor/',
    //         success: function (res) {
    //             if (res.success == 1)
    //             {
    //                 $('#tbl_list_tag tbody').html('');
    //                 render(res.data);
    //                 $('#grid-container').html('');
    //             }
    //         }
    //     });
    // });

    function render(data)
    {
        $.each(data, function (k, v) {
            $('#tbl_list_tag tbody').append('<tr><td>' + k + '</td><td>' + v.toString() + '</td><td><a href="#" class="open-list-unit" data-floor=' + k + '><i class="fa fa-pencil"></i></a></td></tr>');
        });
    }
});


