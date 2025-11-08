
/* To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function () {
//    $('#div_ownership').hide();
//    $('#div_material').hide();
    $('#vw_district_id').on('change', function (e) {
        e.preventDefault();
        get_block_list('vw_block_id', $('#vw_district_id').val());
    });
    $('#district_id').on('change', function (e) {
        e.preventDefault();
        get_block_list('block_id', $('#district_id').val());
    });
    $('#ownership_id').on('change', function (e) {
        e.preventDefault();
        if($('#ownership_id').val() < 50) {
            $('#div_ownership').hide();
        } else {
            $('#div_ownership').show();
        }
    });
    $('#material_id').on('change', function (e) {
        e.preventDefault();
        if($('#material_id').val() < 50) {
            $('#div_material').show();
            $('#div_material div div div span').attr('style', '');
        } else {
            $('#div_material').hide();
        }
    });  
    $('#foundation_id').on('change', function (e) {
        e.preventDefault();
        if($('#foundation_id').val() > 1) {
            //$('#superstructure_id option[value="4"]').wrap('<span>').hide();
            //$('#superstructure_id option[value="4"]').hide();
            //$('#superstructure_id option[value="4"]').attr('disabled','disabled');
        } else {
            //$('#superstructure_id option[value="4"]').show();
            //$('#superstructure_id option[value="4"]').unwrap();
        }
    });
    $('#image_side').on('click', function (e) {
        $('.dropify-clear').on('click', function (e) { 
            $('#image_side').attr('required',true);
        });
    });
    $('#image_alignment').on('click', function (e) {
        $('.dropify-clear').on('click', function (e) { 
            $('#image_alignment').attr('required',true);
        });
    });
    $('image_a1').on('click', function (e) {
        $('.dropify-clear').on('click', function (e) { 
            $('image_a1').attr('required',true);
        });
    });
    $('image_a2').on('click', function (e) {
        $('.dropify-clear').on('click', function (e) { 
            $('image_a2').attr('required',true);
        });
    });
});
function get_block_list(dropdown, district) {
    $('#' + dropdown).empty();
    $('#' + dropdown).append($('<option>', {value: '0', text: '--Select Block--'}));
    if(district > 0) {
        $.ajax({
            url: baseURL + '/bridge/get_block_list',
            type: 'get',
            data: {district_id: district},
            dataType: 'json',
            async: false
        }).done(function (data) {
            if (data.length > 0) {                
                $.each(data, function (i, item) {
                    $('#' + dropdown).append($('<option>', {value: item.id, text: item.name}));
                });
            }
        });
    }
}

function back() {
    window.location.href = baseURL + '/bridge/';
}

function _document(url) {
    window.open(url, '_blank');
}

function add(id) {
    window.location.href = baseURL + '/bridge/entry/' + id;
}
function image(id) {
    window.location.href = baseURL + '/bridge/image/' + id;
}
function status(id, status) {
    var r = confirm('Are you sure to process this?')
    if (r === true) {
        $.ajax({
            url: baseURL + '/bridge/status',
            type: 'get',
            data: {id: id, status: status},
            dataType: 'json',
            async: false
        }).done(function (data) {
            window.location.href = baseURL + '/bridge/';
        });
    }
}